<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ConsentEvent;
use App\Entity\Customer;
use App\Enum\ConsentSource;
use App\Enum\ConsentStatus;
use App\Repository\ConsentEventRepository;
use App\Repository\CustomerRepository;
use App\Repository\Historical\CustomerEntityRepositoryInterface;
use DateTimeImmutable;

class ConsentService
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly ConsentEventRepository $consentEventRepository,
        private readonly CustomerEntityRepositoryInterface $customerEntityRepository,
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
            $this->customerRepository->persist($customer);
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
        $this->consentEventRepository->persist($consentEvent);
        $this->consentEventRepository->flush();

        return $customer;
    }

    public function showConsentModal(string $externalIdentifier, ?string $salesChannel): bool
    {
        if ($salesChannel !== null) {
            $customer = $this->customerRepository->findByExternalIdentifierAndChannel($externalIdentifier, $salesChannel);

            if ($customer !== null) {
                return !$customer->hasDecided();
            }
        } else {
            $customers = $this->customerRepository->findAllByExternalIdentifier($externalIdentifier);

            if ($customers !== []) {
                return array_filter($customers, fn (Customer $customer) => !$customer->hasDecided()) !== [];
            }
        }

        return $this->customerEntityRepository->existsByEmail($externalIdentifier);
    }
}
