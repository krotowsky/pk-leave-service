<?php

namespace App\RequestTemplate\Domain\Repository;

use App\RequestTemplate\Domain\Model\CustomFieldDefinition;

interface CustomFieldDefinitionRepository
{
    public function find(string $id): ?CustomFieldDefinition;
    public function save(CustomFieldDefinition $definition): void;
    public function findAll(): array;
}
