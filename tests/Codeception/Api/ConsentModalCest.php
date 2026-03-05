<?php

declare(strict_types=1);

namespace Api;

use AppTests\Codeception\Support\ApiTester;

class ConsentModalCest
{
    private const SALES_CHANNEL = 'lensplaza_nl';
    private const EXTERNAL_IDENTIFIER = 'test-customer-123@test.com';

    public function modalIsShown(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost(
            '/modal/should-show',
            [
                'external_identifier' => self::EXTERNAL_IDENTIFIER,
                'sales_channel' => self::SALES_CHANNEL,
            ],
        );

        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertTrue($response['show_modal']);
    }
}
