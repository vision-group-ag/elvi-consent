<?php

declare(strict_types=1);

namespace App\Dto;

final class ShowModalRequest
{
    public function __construct(
        public readonly string $externalIdentifier,
        public readonly ?string $salesChannel = null,
    ) {
    }
}
