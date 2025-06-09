<?php

namespace App\LeaveRequest\Application\CommandHandler;

use App\LeaveRequest\Application\Command\RequestLeaveCommand;
use App\LeaveRequest\Domain\LeaveRequestRepository;
use App\LeaveRequest\Domain\LeaveRequest;

class RequestLeaveHandler
{
    public function __construct(
        private LeaveRequestRepository $repository
    ) {}

    public function __invoke(RequestLeaveCommand $command): void
    {
        $leaveRequest = LeaveRequest::request(
            $command->employeeId,
            $command->startDate,
            $command->endDate,
            $command->reason
        );

        $this->repository->save($leaveRequest);
    }
}
