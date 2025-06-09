<?php

namespace App\RequestTemplate\UI\Http;

use App\RequestTemplate\Domain\Model\RequestTemplate;
use App\RequestTemplate\UI\Http\DTO\SaveRequestTemplateDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class AdminRequestTemplateController extends AbstractController
{

    #[Route('/api/admin/request-templates', name: 'create_request_template', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['id', 'name', 'validationRules'],
            properties: [
                new OA\Property(property: 'id', type: 'string', example: 'template-uuid-123'),
                new OA\Property(property: 'name', type: 'string', example: 'Leave Request Template'),
                new OA\Property(
                    property: 'validationRules',
                    type: 'object',
                    additionalProperties: new OA\AdditionalProperties(type: 'object'),
                    example: [
                        'startDate' => ['type' => 'date', 'required' => true],
                        'endDate' => ['type' => 'date', 'required' => true],
                        'reason' => ['type' => 'string', 'minLength' => 5]
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Template saved successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'saved'),
                new OA\Property(property: 'templateId', type: 'string', example: 'template-uuid-123')
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation error',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    additionalProperties: new OA\AdditionalProperties(type: 'string'),
                    example: [
                        'name' => 'This field is required.',
                        'validationRules.reason.minLength' => 'This value is too short.'
                    ]
                )
            ]
        )
    )]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $dto = new SaveRequestTemplateDTO($data);

        $violations = $validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse(['status' => 'error', 'errors' => $errors], 400);
        }

        $existing = $em->getRepository(RequestTemplate::class)->find($dto->id);

        $template = $existing ?? new RequestTemplate($dto->id, $dto->name, []);
        $template->setValidationRules($dto->validationRules);

        $em->persist($template);
        $em->flush();

        return new JsonResponse(['status' => 'saved', 'templateId' => $dto->id]);
    }
}
