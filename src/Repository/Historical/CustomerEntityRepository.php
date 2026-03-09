<?php

declare(strict_types=1);

namespace App\Repository\Historical;

use Doctrine\DBAL\Connection;

class CustomerEntityRepository implements CustomerEntityRepositoryInterface
{
    public function __construct(private Connection $historicalConnection)
    {
    }


    #[\Override]
    public function existsByEmail(string $email): bool
    {
        return (bool) $this->historicalConnection->fetchOne(
            'SELECT 1 FROM customer_entity WHERE email = ? LIMIT 1',
            [$email],
        );
    }
}
