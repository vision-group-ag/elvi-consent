<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final class ConsentDecisionRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $externalIdentifier,
        public readonly ?DateTimeImmutable $decidedAt = null,
        public readonly ?string $consentVersion = null,
    ) {
    }
}
