<?php

declare(strict_types=1);

namespace App\State\Processor;

use App\Entity\Customer;
use App\Enum\ConsentSource;
use DateTimeImmutable;

readonly class ConsentModalOptInProcessor extends AbstractConsentDecisionProcessor
{
    #[\Override]
    protected function record(
        string $externalIdentifier,
        string $salesChannel,
        array $rawData,
        ?DateTimeImmutable $decidedAt,
        ?string $ipAddress,
        ?string $userAgent,
    ): Customer {
        return $this->consentService->recordOptIn(
            externalIdentifier: $externalIdentifier,
            salesChannel: $salesChannel,
            rawData: $rawData,
            source: ConsentSource::ShopModal,
            decidedAt: $decidedAt,
            ipAddress: $ipAddress,
            userAgent: $userAgent,
        );
    }
}
