<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ConsentSource;
use App\Enum\ConsentStatus;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class ConsentEvent
{
    private string $id;
    private DateTimeImmutable $createdAt;

    public function __construct(
        private Customer $customer,
        private ConsentSource $source,
        private ConsentStatus $eventType,
        private array $metadata = [],
        private ?string $consentVersionSlug = null,
        private ?string $consentText = null,
        private ?string $ipAddress = null,
        private ?string $userAgent = null,
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getSource(): ConsentSource
    {
        return $this->source;
    }

    public function getEventType(): ConsentStatus
    {
        return $this->eventType;
    }

    public function getConsentVersionSlug(): ?string
    {
        return $this->consentVersionSlug;
    }

    public function getConsentText(): ?string
    {
        return $this->consentText;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
