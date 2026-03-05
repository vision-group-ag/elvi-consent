<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;

final class ConsentDecisionRequest
{
    public function __construct(
        public readonly string $externalIdentifier,
        public readonly ?DateTimeImmutable $decidedAt = null,
        public readonly ?string $consentVersion = null,
    ) {
    }
}
