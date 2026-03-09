<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConsentEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<ConsentEvent> */
class ConsentEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsentEvent::class);
    }

    public function persist(ConsentEvent $event): void
    {
        $this->getEntityManager()->persist($event);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
