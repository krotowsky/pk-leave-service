<?php

namespace App\LeaveRequest\Application\Command;

class RequestLeaveCommand
{
    public function __construct(
        public string $employeeId,
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate,
        public string $reason
    ) {}
}