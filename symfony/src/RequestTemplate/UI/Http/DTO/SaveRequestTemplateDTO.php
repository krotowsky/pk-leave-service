<?php

namespace App\RequestTemplate\UI\Http\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SaveRequestTemplateDTO
{
    #[Assert\NotBlank]
    public string $id;

    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotNull]
    #[Assert\Type('array')]
    public array $validationRules;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->validationRules = $data['validationRules'] ?? [];
    }
}
