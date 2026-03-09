<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ConsentStatus;
use App\Enum\ImportStatus;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class Customer
{
    private string $id;
    private DateTimeImmutable $createdAt;

    private ?string $dataImportStatus = null;
    private ?DateTimeImmutable $dataImportedAt = null;

    public function __construct(
        private string $externalIdentifier,
        private string $salesChannel,
        private array $rawData,
        private ConsentStatus $consentStatus = ConsentStatus::Pending,
        private ?DateTimeImmutable $decisionDate = null,
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getExternalIdentifier(): string
    {
        return $this->externalIdentifier;
    }

    public function getSalesChannel(): string
    {
        return $this->salesChannel;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function getConsentStatus(): ConsentStatus
    {
        return $this->consentStatus;
    }

    public function getDecisionDate(): ?DateTimeImmutable
    {
        return $this->decisionDate;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function optIn(?DateTimeImmutable $decisionDate = null): void
    {
        $this->consentStatus = ConsentStatus::OptedIn;
        $this->decisionDate = $decisionDate ?? new DateTimeImmutable();
    }

    public function optOut(?DateTimeImmutable $decisionDate = null): void
    {
        $this->consentStatus = ConsentStatus::OptedOut;
        $this->decisionDate = $decisionDate ?? new DateTimeImmutable();
    }

    public function hasDecided(): bool
    {
        return $this->consentStatus !== ConsentStatus::Pending;
    }

    public function getDataImportStatus(): ?string
    {
        return $this->dataImportStatus;
    }

    public function getDataImportedAt(): ?DateTimeImmutable
    {
        return $this->dataImportedAt;
    }

    public function recordDataImport(ImportStatus $status, DateTimeImmutable $importedAt): void
    {
        $this->dataImportStatus = $status->value;
        $this->dataImportedAt = $importedAt;
    }
}
