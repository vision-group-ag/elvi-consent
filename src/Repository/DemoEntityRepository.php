<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DemoEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<DemoEntity> */
class DemoEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoEntity::class);
    }

    public function findByName(string $name): ?DemoEntity
    {
        return $this->findOneBy(['name' => $name]);
    }
}
