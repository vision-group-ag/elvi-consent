<?php

declare(strict_types=1);

namespace App\Repository\Historical;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Psr\Log\LoggerInterface;

class CustomerEntityRepository implements CustomerEntityRepositoryInterface
{
    public function __construct(
        private Connection $historicalConnection,
        private LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function existsByEmail(string $email): bool
    {
        try {
            return (bool) $this->historicalConnection->fetchOne(
                'SELECT 1 FROM customer_entity WHERE email = ? LIMIT 1',
                [$email],
            );
        } catch (DBALException $e) {
            $this->logger->warning('Historical DB unavailable, treating customer as unknown.', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
