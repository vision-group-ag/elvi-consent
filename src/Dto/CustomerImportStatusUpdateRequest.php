<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ImportStatus;

final class CustomerImportStatusUpdateRequest
{
    public function __construct(
        public readonly string $email,
        public readonly \DateTimeImmutable $importedAt,
        public readonly ?string $salesChannel = null,
        public readonly ?ImportStatus $status = ImportStatus::Completed,
    ) {
    }
}
