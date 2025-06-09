<?php

namespace App\LeaveRequest\Infrastructure\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RequestLeaveDTO
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public string $employeeId;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $startDate;

    #[Assert\NotBlank]
    #[Assert\Date]
    public string $endDate;

    #[Assert\NotBlank]
    public string $reason;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        try {
            $start = new \DateTimeImmutable($this->startDate);
            $end = new \DateTimeImmutable($this->endDate);
            $now = new \DateTimeImmutable();
            $minStart = $now->modify('+1 day'); // or make this configurable later

            if ($start < $minStart) {
                $context->buildViolation('Start date must be at least 1 day in the future.')
                    ->atPath('startDate')
                    ->addViolation();
            }

            if ($end <= $start) {
                $context->buildViolation('End date must be after start date.')
                    ->atPath('endDate')
                    ->addViolation();
            }

            if (mb_strlen($this->reason) < 3) {
                $context->buildViolation('Reason must be at least 3 characters long.')
                    ->atPath('reason')
                    ->addViolation();
            }

        } catch (\Exception) {
            // Let Assert\Date handle bad date formats
        }
    }
}
