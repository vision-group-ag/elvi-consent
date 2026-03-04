<?php

declare(strict_types=1);

namespace App\Exception;

use App\Enum\ConsentStatus;
use RuntimeException;

final class AlreadyDecidedException extends RuntimeException
{
    public function __construct(private readonly ConsentStatus $currentStatus)
    {
        parent::__construct('Customer has already made a consent decision.');
    }

    public function getCurrentStatus(): ConsentStatus
    {
        return $this->currentStatus;
    }
}
