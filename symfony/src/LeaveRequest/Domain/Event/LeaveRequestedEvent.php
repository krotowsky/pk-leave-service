<?php

namespace App\LeaveRequest\Domain\Event;

class LeaveRequestedEvent
{
    public function __construct(
        public string $leaveRequestId,
        public string $employeeId,
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate,
        public string $reason
    ) {}
}
