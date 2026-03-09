<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ImportStatus;

class CustomerImportStatusUpdateOutput
{
    public function __construct(
        public readonly ImportStatus $status,
        public readonly ?string $message,
    ) {
    }
}
