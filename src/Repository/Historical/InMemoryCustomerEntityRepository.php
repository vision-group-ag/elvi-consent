<?php

declare(strict_types=1);

namespace App\Repository\Historical;

class InMemoryCustomerEntityRepository implements CustomerEntityRepositoryInterface
{
    /** @param string[] $emails */
    public function __construct(private array $emails = [])
    {
    }

    #[\Override]
    public function existsByEmail(string $email): bool
    {
        return in_array($email, $this->emails, true);
    }
}
