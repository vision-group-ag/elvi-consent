<?php

declare(strict_types=1);

namespace AppTests\Codeception\Api;

use App\Enum\ConsentStatus;
use App\Enum\ImportStatus;
use AppTests\Codeception\Support\ApiTester;

class CustomerImportStatusCest
{
    private const SALES_CHANNEL = 'lensplaza_nl';
    private const EMAIL = 'test-customer-123@test.com';
    private const IMPORTED_AT = '2026-03-09T10:00:00+00:00';

    public function updatesImportStatusWithSalesChannel(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::EMAIL,
            'salesChannel' => self::SALES_CHANNEL,
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);

        $I->sendPost('/customers/import-status', [
            'email' => self::EMAIL,
            'sales_channel' => self::SALES_CHANNEL,
            'imported_at' => self::IMPORTED_AT,
        ]);

        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals(ImportStatus::Completed->value, $response['status']);
    }

    public function updatesImportStatusWithExplicitStatus(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::EMAIL,
            'salesChannel' => self::SALES_CHANNEL,
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);

        $I->sendPost('/customers/import-status', [
            'email' => self::EMAIL,
            'sales_channel' => self::SALES_CHANNEL,
            'status' => ImportStatus::Failed->value,
            'imported_at' => self::IMPORTED_AT,
        ]);

        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals(ImportStatus::Failed->value, $response['status']);
    }

    public function updatesImportStatusWithoutSalesChannel(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->haveInRepository(\App\Entity\Customer::class, [
            'externalIdentifier' => self::EMAIL,
            'salesChannel' => self::SALES_CHANNEL,
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);

        $I->sendPost('/customers/import-status', [
            'email' => self::EMAIL,
            'imported_at' => self::IMPORTED_AT,
        ]);

        $I->seeResponseCodeIs(200);
        $response = $I->grabResponseJsonData();
        $I->assertEquals(ImportStatus::Completed->value, $response['status']);
    }

    public function returnsNotFoundForUnknownCustomer(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('/customers/import-status', [
            'email' => 'unknown@test.com',
            'sales_channel' => self::SALES_CHANNEL,
            'imported_at' => self::IMPORTED_AT,
        ]);

        $I->seeResponseCodeIs(404);
    }

    public function returnsBadRequestWhenEmailMissing(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('/customers/import-status', [
            'imported_at' => self::IMPORTED_AT,
        ]);

        $I->seeResponseCodeIs(400);
    }

    public function returnsBadRequestWhenImportedAtMissing(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');

        $I->sendPost('/customers/import-status', [
            'email' => self::EMAIL,
        ]);

        $I->seeResponseCodeIs(400);
    }
}
