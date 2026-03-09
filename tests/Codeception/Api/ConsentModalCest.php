<?php

declare(strict_types=1);

namespace Api;

use App\Enum\ConsentStatus;
use AppTests\Codeception\Support\ApiTester;

class ConsentModalCest
{
    private const SALES_CHANNEL = 'lensplaza_nl';
    private const KNOWN_EMAIL = 'test-customer-123@test.com'; // in InMemoryCustomerEntityRepository
    private const UNKNOWN_EMAIL = 'brand-new@test.com';       // not in InMemory repo

    // --- With salesChannel ---

    public function modalIsShownWhenEmailIsInHistoricalDb(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::KNOWN_EMAIL,
            'sales_channel' => self::SALES_CHANNEL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertTrue($I->grabResponseJsonData()['show_modal']);
    }

    public function modalIsShownWhenCustomerIsPending(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::KNOWN_EMAIL,
            'salesChannel' => self::SALES_CHANNEL,
            'rawData' => [],
            'consentStatus' => ConsentStatus::Pending,
            'dataImportStatus' => 'completed',
            'dataImportedAt' => new \DateTimeImmutable(),
        ]);

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::KNOWN_EMAIL,
            'sales_channel' => self::SALES_CHANNEL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertTrue($I->grabResponseJsonData()['show_modal']);
    }

    public function modalIsNotShownWhenCustomerOptedIn(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::KNOWN_EMAIL,
            'salesChannel' => self::SALES_CHANNEL,
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::KNOWN_EMAIL,
            'sales_channel' => self::SALES_CHANNEL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertFalse($I->grabResponseJsonData()['show_modal']);
    }

    public function modalIsNotShownWhenCustomerOptedOut(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::KNOWN_EMAIL,
            'salesChannel' => self::SALES_CHANNEL,
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedOut,
        ]);

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::KNOWN_EMAIL,
            'sales_channel' => self::SALES_CHANNEL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertFalse($I->grabResponseJsonData()['show_modal']);
    }

    public function modalIsNotShownWhenEmailUnknown(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::UNKNOWN_EMAIL,
            'sales_channel' => self::SALES_CHANNEL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertFalse($I->grabResponseJsonData()['show_modal']);
    }

    // --- Without salesChannel ---

    public function modalIsShownWithoutChannelWhenEmailIsInHistoricalDb(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::KNOWN_EMAIL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertTrue($I->grabResponseJsonData()['show_modal']);
    }

    public function modalIsShownWithoutChannelWhenAnyCustomerIsPending(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::KNOWN_EMAIL,
            'salesChannel' => 'lensplaza_nl',
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);
        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::KNOWN_EMAIL,
            'salesChannel' => 'lensplaza_be',
            'rawData' => [],
            'consentStatus' => ConsentStatus::Pending,
        ]);

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::KNOWN_EMAIL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertTrue($I->grabResponseJsonData()['show_modal']);
    }

    public function modalIsNotShownWithoutChannelWhenAllCustomersDecided(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::KNOWN_EMAIL,
            'salesChannel' => 'lensplaza_nl',
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);
        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::KNOWN_EMAIL,
            'salesChannel' => 'lensplaza_be',
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedOut,
        ]);

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::KNOWN_EMAIL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertFalse($I->grabResponseJsonData()['show_modal']);
    }

    public function modalIsNotShownWithoutChannelWhenEmailUnknown(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('/modal/should-show', [
            'external_identifier' => self::UNKNOWN_EMAIL,
        ]);

        $I->seeResponseCodeIs(200);
        $I->assertFalse($I->grabResponseJsonData()['show_modal']);
    }
}
