<?php

namespace App\LeaveRequest\UI\Http;

use App\LeaveRequest\Domain\Model\LeaveRequestValidationSettings;
use App\LeaveRequest\UI\Http\DTO\LeaveValidationSettingsDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminLeaveValidationSettingsController extends AbstractController
{
    #[Route('/admin/leave-validation-settings', name: 'admin_leave_validation_settings', methods: ['GET', 'POST'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $settings = $em->getRepository(LeaveRequestValidationSettings::class)->find('global');

        if (!$settings) {
            $settings = new LeaveRequestValidationSettings();
            $settings->setId('global');
        }

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);

            $dto = new LeaveValidationSettingsDTO($data);

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

            $settings->setMinReasonLength($dto->minReasonLength);
            $settings->setMinStartDelayDays($dto->minStartDelayDays);

            $em->persist($settings);
            $em->flush();

            return new JsonResponse(['status' => 'updated']);
        }

        return new JsonResponse([
            'minReasonLength' => $settings->getMinReasonLength(),
            'minStartDelayDays' => $settings->getMinStartDelayDays(),
        ]);
    }
}
