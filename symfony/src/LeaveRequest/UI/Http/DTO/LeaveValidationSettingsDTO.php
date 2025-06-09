<?php

namespace App\LeaveRequest\UI\Http\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class LeaveValidationSettingsDTO
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(1)]
    public int $minReasonLength;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    #[Assert\GreaterThanOrEqual(1)]
    public int $minStartDelayDays;

    public function __construct(array $data)
    {
        $this->minReasonLength = isset($data['minReasonLength']) ? (int) $data['minReasonLength'] : 0;
        $this->minStartDelayDays = isset($data['minStartDelayDays']) ? (int) $data['minStartDelayDays'] : 0;
    }
}
