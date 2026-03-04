<?php

declare(strict_types=1);

namespace App\Dto;

final class ConsentDecisionOutput
{
    public function __construct(
        public readonly string $consentStatus,
    ) {
    }
}
