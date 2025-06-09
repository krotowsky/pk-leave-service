<?php

namespace App\RequestTemplate\Domain\Repository;

use App\RequestTemplate\Domain\Model\RequestTemplate;

interface RequestTemplateRepository
{
    public function find(string $id): ?RequestTemplate;
}
