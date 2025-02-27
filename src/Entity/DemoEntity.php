<?php

declare(strict_types=1);

namespace App\Entity;

readonly class DemoEntity
{
    public function __construct(
        private string $id,
        private string $name,
        private bool $isPublic = false
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }
}
