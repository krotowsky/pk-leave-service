<?php

namespace App\LeaveRequest\UI\Http;

use App\LeaveRequest\Application\Command\RequestLeaveCommand;
use App\LeaveRequest\Domain\LeaveRequest;
use App\LeaveRequest\Infrastructure\DTO\RequestLeaveDTO;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class LeaveRequestController extends AbstractController
{
    #[Route('/api/leave-request', name: 'api_leave_request', methods: ['POST'])]
    #[OA\Post(
        path: '/api/leave-request',
        summary: 'Submit a new leave request',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['employeeId', 'startDate', 'endDate', 'reason'],
                properties: [
                    new OA\Property(property: 'employeeId', type: 'string', example: '12345'),
                    new OA\Property(property: 'startDate', type: 'string', format: 'date', example: '2025-07-01'),
                    new OA\Property(property: 'endDate', type: 'string', format: 'date', example: '2025-07-10'),
                    new OA\Property(property: 'reason', type: 'string', example: 'Vacation')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Leave request submitted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'Leave request submitted')
                    ]
                )
            ),
            new OA\Response(
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
                                'employeeId' => 'This field is required.',
                                'startDate' => 'Invalid date format.'
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function requestLeave(
        Request $request,
        MessageBusInterface $commandBus,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $dto = new RequestLeaveDTO();
        $dto->employeeId = $data['employeeId'] ?? '';
        $dto->startDate = $data['startDate'] ?? '';
        $dto->endDate = $data['endDate'] ?? '';
        $dto->reason = $data['reason'] ?? '';

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

        $command = new RequestLeaveCommand(
            $dto->employeeId,
            new \DateTimeImmutable($dto->startDate),
            new \DateTimeImmutable($dto->endDate),
            $dto->reason
        );

        $commandBus->dispatch($command);

        return new JsonResponse([
            'status' => 'Leave request submitted',
        ], JsonResponse::HTTP_CREATED);
    }
}
