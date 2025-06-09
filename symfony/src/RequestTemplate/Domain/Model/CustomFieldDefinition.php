<?php

namespace App\RequestTemplate\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class CustomFieldDefinition
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid")]
    private Uuid $id;

    #[ORM\Column(type: "string")]
    private string $name;

    #[ORM\Column(type: "string")]
    private string $type;

    #[ORM\Column(type: "boolean")]
    private bool $required;

    #[ORM\Column(type: 'json')]
    private array $config = [];

    public function __construct(string $name, string $type, bool $required, array $config)
    {
        $this->id = Uuid::v4();
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->config = $config;
    }

    // opcjonalnie gettery:
    public function getId(): Uuid { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getType(): string { return $this->type; }
    public function isRequired(): bool { return $this->required; }
    public function getConfig(): array { return $this->config; }
}
