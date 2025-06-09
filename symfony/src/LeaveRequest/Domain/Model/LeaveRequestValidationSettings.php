<?php

namespace App\LeaveRequest\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'leave_request_validation_settings')]
class LeaveRequestValidationSettings
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 100)]
    private string $id = 'global'; // single record or use tenant ID

    #[ORM\Column(type: 'integer')]
    private int $minReasonLength = 3;

    #[ORM\Column(type: 'integer')]
    private int $minStartDelayDays = 1;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getMinReasonLength(): int
    {
        return $this->minReasonLength;
    }

    public function setMinReasonLength(int $minReasonLength): void
    {
        $this->minReasonLength = $minReasonLength;
    }

    public function getMinStartDelayDays(): int
    {
        return $this->minStartDelayDays;
    }

    public function setMinStartDelayDays(int $minStartDelayDays): void
    {
        $this->minStartDelayDays = $minStartDelayDays;
    }
}
