<?php

namespace App\RequestTemplate\UI\Http;

use App\RequestTemplate\Domain\Repository\CustomFieldDefinitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListCustomFieldsController extends AbstractController
{
    public function __construct(private readonly CustomFieldDefinitionRepository $repository) {}

    #[Route('/custom-fields', name: 'list_custom_fields', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $fields = $this->repository->findAll();

        $data = array_map(function ($field) {
            return [
                'id' => $field->getId(),
                'name' => $field->getName(),
                'type' => $field->getType(),
                'required' => $field->isRequired(),
                'config' => $field->getConfig(),
            ];
        }, $fields);

        return new JsonResponse($data);
    }
}
