<?php

declare(strict_types=1);

namespace App\Entity;

class DemoEntity
{
    private string $id;
    private string $name;
    private bool $isPublic;

    public function __construct(string $id, string $name, $isPublic = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isPublic = $isPublic;
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
