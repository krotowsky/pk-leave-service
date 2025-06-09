<?php

namespace App\LeaveRequest\Infrastructure\Validator;

use App\LeaveRequest\Application\Service\LeaveValidationRuleProvider;
use App\LeaveRequest\Infrastructure\DTO\RequestLeaveDTO;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RequestLeaveValidator
{
    public function __construct(
        private readonly LeaveValidationRuleProvider $ruleProvider
    ) {}

    public function __invoke(RequestLeaveDTO $dto, ExecutionContextInterface $context): void
    {
        try {
            $rules = $this->ruleProvider->getRules();

            $start = new \DateTimeImmutable($dto->startDate);
            $end = new \DateTimeImmutable($dto->endDate);
            $now = new \DateTimeImmutable();
            $minStart = $now->modify("+{$rules->getMinStartDelayDays()} days");

            if ($start < $minStart) {
                $context->buildViolation("Start date must be at least {$rules->getMinStartDelayDays()} day(s) in the future.")
                    ->atPath('startDate')
                    ->addViolation();
            }

            if ($end <= $start) {
                $context->buildViolation("End date must be after start date.")
                    ->atPath('endDate')
                    ->addViolation();
            }

            if (mb_strlen($dto->reason) < $rules->getMinReasonLength()) {
                $context->buildViolation("Reason must be at least {$rules->getMinReasonLength()} characters long.")
                    ->atPath('reason')
                    ->addViolation();
            }

        } catch (\Exception $e) {
            // Ignore parse errors — already handled by Assert\Date
        }
    }
}
