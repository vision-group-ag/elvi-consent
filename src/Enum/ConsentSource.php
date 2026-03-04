<?php

declare(strict_types=1);

namespace App\Enum;

enum ConsentSource: string
{
    case LandingPage = 'landing_page';
    case ShopModal = 'shop_modal';
}
