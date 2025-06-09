<?php

namespace App\RequestTemplate\UI\Http;

use App\RequestTemplate\Domain\Model\CustomFieldDefinition;
use App\RequestTemplate\Domain\Repository\CustomFieldDefinitionRepository;
use App\RequestTemplate\UI\Http\DTO\SaveCustomFieldDefinitionDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminCustomFieldController extends AbstractController
{
    public function __construct(private readonly CustomFieldDefinitionRepository $repository) {}

    #[Route('/custom-fields', name: 'create_custom_field', methods: ['POST'])]
    public function __invoke(
        Request $request,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $dto = new SaveCustomFieldDefinitionDTO($data);

        $violations = $validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse([
                'status' => 'error',
                'errors' => $errors,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $field = new CustomFieldDefinition(
            name: $dto->name,
            type: $dto->type,
            required: $dto->required,
            config: $dto->config
        );

        $this->repository->save($field);

        return new JsonResponse([
            'status' => 'ok',
            'id' => $field->getId(),
        ], Response::HTTP_CREATED);
    }
}
