<?php

namespace App\LeaveRequest\Domain;

interface LeaveRequestRepository
{
    public function save(LeaveRequest $leaveRequest): void;
    public function get(string $id): ?LeaveRequest;
}
