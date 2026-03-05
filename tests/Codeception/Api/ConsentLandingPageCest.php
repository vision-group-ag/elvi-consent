<?php

declare(strict_types=1);

namespace AppTests\Codeception\Api;

use AppTests\Codeception\Support\ApiTester;

class ConsentLandingPageCest
{
    private const SALES_CHANNEL = 'lensplaza_nl';
    private const EXTERNAL_IDENTIFIER = 'test-customer-123@test.com';

    public function optInCreatesCustomerAndReturnsOptedIn(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optin',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );

        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals('opted_in', $response['consent_status']);
    }

    public function optInTwiceReturnsOptedIn(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optin',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );
        $I->seeResponseCodeIs(200);

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optin',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );
        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals('opted_in', $response['consent_status']);
    }

    public function optOutCreatesCustomerAndReturnsOptedOut(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optout',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );

        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals('opted_out', $response['consent_status']);
    }

    public function optOutTwiceReturnsOptedOut(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optout',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );
        $I->seeResponseCodeIs(200);

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optout',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );
        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals('opted_out', $response['consent_status']);
    }

    public function optInAfterOptOutReturnsOptedIn(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optout',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );
        $I->seeResponseCodeIs(200);

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optin',
            ['external_identifier' => self::EXTERNAL_IDENTIFIER],
        );
        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals('opted_in', $response['consent_status']);
    }

    public function missingExternalIdentifierReturnsBadRequest(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost(
            '/landing-page/' . self::SALES_CHANNEL . '/optin',
            [],
        );

        $I->seeResponseCodeIs(400);
    }
}
