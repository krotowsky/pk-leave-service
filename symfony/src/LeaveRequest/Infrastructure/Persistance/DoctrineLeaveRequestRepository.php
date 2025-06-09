<?php

namespace App\LeaveRequest\Infrastructure\Persistence;

use App\LeaveRequest\Domain\LeaveRequest;
use App\LeaveRequest\Domain\LeaveRequestRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineLeaveRequestRepository implements LeaveRequestRepository
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function save(LeaveRequest $leaveRequest): void
    {
        $this->em->persist($leaveRequest);
        $this->em->flush();
    }

    public function get(string $id): ?LeaveRequest
    {
        return $this->em->find(LeaveRequest::class, $id);
    }
}