<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Customer> */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findByExternalIdentifierAndChannel(string $externalIdentifier, string $salesChannel): ?Customer
    {
        return $this->findOneBy([
            'externalIdentifier' => $externalIdentifier,
            'salesChannel' => $salesChannel,
        ]);
    }

    public function findOneByExternalIdentifier(string $externalIdentifier): ?Customer
    {
        return $this->findOneBy([
            'externalIdentifier' => $externalIdentifier,
        ]);
    }

    public function findFromToDateRange(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.decisionDate >= :from')
            ->andWhere('c.decisionDate <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        return $qb->getQuery()->getResult();
    }

    public function persist(Customer $customer): void
    {
        $this->getEntityManager()->persist($customer);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}
