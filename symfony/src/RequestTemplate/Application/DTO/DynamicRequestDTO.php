<?php

namespace App\RequestTemplate\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DynamicRequestDTO
{
    #[Assert\NotBlank]
    public string $templateId;

    #[Assert\Type('array')]
    #[Assert\NotNull]
    public array $fields = [];
}
