<?php

declare(strict_types=1);

namespace App\Dto;

final class ShowModalOutput
{
    public function __construct(
        public readonly bool $showModal,
    ) {
    }
}
