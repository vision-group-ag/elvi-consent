<?php

declare(strict_types=1);

namespace App\Repository\Historical;

interface CustomerEntityRepositoryInterface
{
    public function existsByEmail(string $email): bool;
}
