<?php

namespace App\RequestTemplate\Infrastructure\Persistence;

use App\RequestTemplate\Domain\Model\CustomFieldDefinition;
use App\RequestTemplate\Domain\Repository\CustomFieldDefinitionRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCustomFieldDefinitionRepository implements CustomFieldDefinitionRepository
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function find(string $id): ?CustomFieldDefinition
    {
        return $this->em->getRepository(CustomFieldDefinition::class)->find($id);
    }

    public function save(CustomFieldDefinition $definition): void
    {
        $this->em->persist($definition);
        $this->em->flush();
    }

    /** @return CustomFieldDefinition[] */
    public function findAll(): array
    {
        return $this->em->getRepository(CustomFieldDefinition::class)->findAll();
    }
}
