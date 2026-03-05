<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ConsentEvent;
use App\Entity\Customer;
use App\Enum\ConsentSource;
use App\Enum\ConsentStatus;
use App\Repository\CustomerRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ConsentService
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function recordOptIn(
        string $externalIdentifier,
        string $salesChannel,
        array $rawData,
        ConsentSource $source,
        ?DateTimeImmutable $decidedAt = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): Customer {
        return $this->recordDecision(
            ConsentStatus::OptedIn,
            $externalIdentifier,
            $salesChannel,
            $rawData,
            $source,
            $decidedAt,
            $ipAddress,
            $userAgent,
        );
    }

    public function recordOptOut(
        string $externalIdentifier,
        string $salesChannel,
        array $rawData,
        ConsentSource $source,
        ?DateTimeImmutable $decidedAt = null,
        ?string $ipAddress = null,
        ?string $userAgent = null,
    ): Customer {
        return $this->recordDecision(
            ConsentStatus::OptedOut,
            $externalIdentifier,
            $salesChannel,
            $rawData,
            $source,
            $decidedAt,
            $ipAddress,
            $userAgent,
        );
    }

    private function recordDecision(
        ConsentStatus $decision,
        string $externalIdentifier,
        string $salesChannel,
        array $rawData,
        ConsentSource $source,
        ?DateTimeImmutable $decidedAt,
        ?string $ipAddress,
        ?string $userAgent,
    ): Customer {
        $customer = $this->customerRepository->findByExternalIdentifierAndChannel($externalIdentifier, $salesChannel);

        if ($customer === null) {
            $customer = new Customer($externalIdentifier, $salesChannel, $rawData);
            $this->entityManager->persist($customer);
        }

        match ($decision) {
            ConsentStatus::OptedIn => $customer->optIn($decidedAt),
            ConsentStatus::OptedOut => $customer->optOut($decidedAt),
            default => null,
        };

        $metadata = $decidedAt !== null ? ['decided_at' => $decidedAt->format(\DateTimeInterface::ATOM)] : [];

        $consentEvent = new ConsentEvent(
            customer: $customer,
            source: $source,
            eventType: $decision,
            metadata: $metadata,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
        );
        $this->entityManager->persist($consentEvent);
        $this->entityManager->flush();

        return $customer;
    }

    public function showConsentModal(string $externalIdentifier, string $salesChannel): bool
    {
        $customer = $this->customerRepository->findByExternalIdentifierAndChannel($externalIdentifier, $salesChannel);

        if ($customer === null) {
            return true;
        }

        return !$customer->hasDecided();
    }
}
