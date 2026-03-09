<?php

declare(strict_types=1);

namespace AppTests\Codeception\Support\Helper;

trait DataHelperTrait
{
    public function haveCustomer(string $email, string $salesChannel): mixed
    {
        return $this->haveInRepository(\App\Entity\Customer::class, [
            'id' => 'cd89b4ea-41aa-11ee-bbf6-0242ac120017',
            'label' => 'Best customers',
            'customerEmail' => $email,
            'salesChannel' => $salesChannel,
            'customerShopAccountId' => 'cd89b4ea-41aa-11ee-bbf6-0242ac120017',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'locale' => 'it_IT',
            'isLinked' => true,
        ]);
    }
}
