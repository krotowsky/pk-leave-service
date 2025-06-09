<?php

namespace App\RequestTemplate\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'request_template')]
class RequestTemplate
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'json')]
    private array $validationRules = [];

    #[ORM\ManyToMany(targetEntity: CustomFieldDefinition::class)]
    #[ORM\JoinTable(
        name: 'template_custom_fields',
        joinColumns: [
            new ORM\JoinColumn(name: 'template_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'field_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $customFields;

    public function __construct(Uuid $id, string $name, array $validationRules = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->validationRules = $validationRules;
        $this->customFields = new ArrayCollection();
    }

    public function getId(): Uuid
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

    public function getCustomFields(): Collection
    {
        return $this->customFields;
    }

    public function addCustomField(CustomFieldDefinition $field): void
    {
        if (!$this->customFields->contains($field)) {
            $this->customFields->add($field);
        }
    }

    public function removeCustomField(CustomFieldDefinition $field): void
    {
        $this->customFields->removeElement($field);
    }

    public function setValidationRules(array $rules): void
    {
        $this->validationRules = $rules;
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }
}
