<?php

namespace App\RequestTemplate\UI\Http;

use App\RequestTemplate\Domain\Repository\RequestTemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class ListTemplatesController extends AbstractController
{
    public function __construct(private readonly RequestTemplateRepository $repository) {}
    #[OA\Get(
        path: '/api/templates',
        summary: 'List all templates',
        tags: ['Templates'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns list of templates',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'string'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'fields', type: 'object')
                        ]
                    )
                )
            )
        ]
    )]
    #[Route('/api/templates', name: 'list_templates', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $templates = $this->repository->findAll();

        $result = array_map(function ($template) {
            return [
                'id' => $template->getId(),
                'name' => $template->getName(),
                'fields' => $template->getValidationRules()
            ];
        }, $templates);

        return new JsonResponse($result);
    }
}
