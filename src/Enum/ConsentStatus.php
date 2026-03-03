<?php

declare(strict_types=1);

namespace App\Enum;

enum ConsentStatus: string
{
    case Pending = 'pending';
    case OptedIn = 'opted_in';
    case OptedOut = 'opted_out';
}
