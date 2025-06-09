<?php

namespace App\RequestTemplate\Infrastructure\Persistence;

use App\RequestTemplate\Domain\Model\RequestTemplate;
use App\RequestTemplate\Domain\Repository\RequestTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineRequestTemplateRepository implements RequestTemplateRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }



    public function find(string $id): ?RequestTemplate
    {
        return $this->em->getRepository(RequestTemplate::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->em->getRepository(RequestTemplate::class)->findAll();
    }
}
