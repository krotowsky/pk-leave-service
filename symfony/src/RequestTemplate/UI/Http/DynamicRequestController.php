<?php

namespace App\RequestTemplate\UI\Http;

use App\RequestTemplate\Application\DTO\DynamicRequestDTO;
use App\RequestTemplate\Application\Service\DynamicRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\ModelDescriber\Annotations;

class DynamicRequestController extends AbstractController
{
    #[Route('/api/dynamic-request', name: 'submit_dynamic_request', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['templateId', 'fields'],
            properties: [
                new OA\Property(property: 'templateId', type: 'string', example: 'template-uuid-123'),
                new OA\Property(
                    property: 'fields',
                    type: 'object',
                    additionalProperties: new OA\AdditionalProperties(type: 'string'),
                    example: [
                        'firstName' => 'John',
                        'reason' => 'Business travel'
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Request accepted',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success'),
                new OA\Property(property: 'message', type: 'string', example: 'Request is valid and has been accepted.')
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(
                    property: 'errors',
                    type: 'object',
                    additionalProperties: new OA\AdditionalProperties(type: 'string')
                )
            ]
        )
    )]

    public function __invoke(
        Request $request,
        DynamicRequestValidator $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $dto = new DynamicRequestDTO();
        $dto->templateId = $data['templateId'] ?? '';
        $dto->fields = $data['fields'] ?? [];

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

        // Simulate saving the request (can be extended to persist)
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Request is valid and has been accepted.',
        ], JsonResponse::HTTP_OK);
    }
}
