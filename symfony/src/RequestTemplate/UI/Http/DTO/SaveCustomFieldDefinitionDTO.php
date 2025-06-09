<?php

namespace App\RequestTemplate\UI\Http\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SaveCustomFieldDefinitionDTO
{
    #[Assert\NotBlank]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['text', 'date', 'number', 'amount', 'date_range'])]
    public string $type;

    #[Assert\NotNull]
    public bool $required;

    #[Assert\Type('array')]
    public array $config;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? '';
        $this->type = $data['type'] ?? '';
        $this->required = (bool)($data['required'] ?? false);
        $this->config = $data['config'] ?? [];
    }
}
