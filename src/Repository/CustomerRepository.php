<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Enum\ConsentStatus;
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

    public function findOneByExternalIdentifierAndStatus(string $externalIdentifier, ConsentStatus $consentStatus): ?Customer
    {
        return $this->findOneBy([
            'externalIdentifier' => $externalIdentifier,
            'consentStatus' => $consentStatus->value,
        ]);
    }

    public function findFromToDateRange(\DateTimeImmutable $from, \DateTimeImmutable $to, ConsentStatus $consentStatus): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.decisionDate >= :from')
            ->andWhere('c.decisionDate <= :to')
            ->andWhere('c.consentStatus = :decision')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('decision', $consentStatus->value);

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

    public function findAllByExternalIdentifier(string $externalIdentifier): array
    {
        return $this->findBy(['externalIdentifier' => $externalIdentifier]);
    }

    public function findOneByExternalIdentifier(string $email): ?Customer
    {
        return $this->findOneBy(['externalIdentifier' => $email]);
    }

    public function findOneByExternalIdentifierAndSalesChannel(string $email, string $salesChannel): ?Customer
    {
        return $this->findOneBy(['externalIdentifier' => $email, 'salesChannel' => $salesChannel]);
    }
}
