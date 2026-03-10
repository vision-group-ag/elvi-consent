<?php

declare(strict_types=1);

namespace App\State\Processor;

use App\Entity\Customer;
use App\Enum\ConsentSource;
use DateTimeImmutable;

readonly class ConsentOptInProcessor extends AbstractConsentDecisionProcessor
{
    #[\Override]
    protected function record(
        string $externalIdentifier,
        string $brand,
        array $rawData,
        ?string $salesChannel,
        ?DateTimeImmutable $decidedAt,
        ?string $ipAddress,
        ?string $userAgent,
    ): Customer {
        return $this->consentService->recordOptIn(
            externalIdentifier: $externalIdentifier,
            salesChannel: $salesChannel,
            rawData: $rawData,
            source: ConsentSource::LandingPage,
            brand: $brand,
            decidedAt: $decidedAt,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
        );
    }
}
