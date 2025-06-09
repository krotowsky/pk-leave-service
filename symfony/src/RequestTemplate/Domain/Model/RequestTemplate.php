<?php

namespace App\RequestTemplate\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'request_templates')]
class RequestTemplate
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 100)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $validationRules = [];

    public function __construct(string $id, string $name, array $validationRules = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->validationRules = $validationRules;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    public function setValidationRules(array $rules): void
    {
        $this->validationRules = $rules;
    }
}
