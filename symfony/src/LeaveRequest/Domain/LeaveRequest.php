<?php

namespace App\LeaveRequest\Domain;

use App\LeaveRequest\Domain\Event\LeaveRequestedEvent;
use Symfony\Component\Uid\Uuid;

use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity]
#[ORM\Table(name: "leave_requests")]
class LeaveRequest
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private Uuid  $id;
    #[ORM\Column(type: "uuid")]
    private Uuid  $employeeId;
    #[ORM\Column(type: "date_immutable")]
    private \DateTimeImmutable $endDate;
    #[ORM\Column(type: "string", length: 255)]
    private string $reason;

    private function __construct() {}

    public static function request(
        string $employeeId,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        string $reason
    ): self {
        $leaveRequest = new self();
        $leaveRequest->id = Uuid::uuid4(); // typ UuidInterface
        $leaveRequest->employeeId = Uuid::fromString($employeeId);
        $leaveRequest->startDate = $startDate;
        $leaveRequest->endDate = $endDate;
        $leaveRequest->reason = $reason;

        // Emit domain event here, e.g., $leaveRequest->recordEvent(new LeaveRequested(...));

        return $leaveRequest;
    }
}