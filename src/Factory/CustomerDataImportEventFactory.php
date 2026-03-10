<?php

declare(strict_types=1);

namespace App\Factory;

use Elvi\EventsBundle\Event\ExternalDataImport\ExternalDataImportRequested;

final class CustomerDataImportEventFactory
{
    public const string CONTEXT = 'elvi-consent';

    public static function create(string $customerEmail): ExternalDataImportRequested
    {
        return new ExternalDataImportRequested(
            entity: 'customer',
            rule: 'all',
            salesChannel: null,
            customerEmail: $customerEmail,
            requestedByContext: self::CONTEXT,
        );
    }
}
