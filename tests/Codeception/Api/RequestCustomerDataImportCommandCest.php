<?php

declare(strict_types=1);

namespace AppTests\Codeception\Api;

use App\Entity\Customer;
use App\Enum\ConsentStatus;
use AppTests\Codeception\Support\ApiTester;

class RequestCustomerDataImportCommandCest
{
    private const COMMAND = 'app:request-customer-data-import';
    private const EMAIL = 'opted-in-customer@test.com';
    private const UNKNOWN_EMAIL = 'unknown-customer@test.com';

    public function noFiltersExitsEarly(ApiTester $I): void
    {
        $I->runSymfonyConsoleCommand(self::COMMAND);

        $I->seeMessageInConsoleOutput('No filters provided');
    }

    public function customerNotFoundWithIdentifier(ApiTester $I): void
    {
        $I->runSymfonyConsoleCommand(self::COMMAND, [
            '--externalIdentifier' => self::UNKNOWN_EMAIL,
        ]);

        $I->seeMessageInConsoleOutput('No customers found');
    }

    public function dryRunShowsTableWithoutDispatching(ApiTester $I): void
    {
        $I->haveInRepository(Customer::class, [
            'externalIdentifier' => self::EMAIL,
            'salesChannel' => 'lensplaza_nl',
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);

        $I->runSymfonyConsoleCommand(self::COMMAND, [
            '--externalIdentifier' => self::EMAIL,
            '--dry-run' => true,
        ]);

        $I->seeMessageInConsoleOutput('Dry run mode enabled');
        $I->seeMessageInConsoleOutput(self::EMAIL);
        $I->dontSeeInConsoleOutput('Dispatching import events');
    }

    public function dispatchesEventForOptedInCustomer(ApiTester $I): void
    {
        $I->haveInRepository(Customer::class, [
            'externalIdentifier' => self::EMAIL,
            'salesChannel' => 'lensplaza_nl',
            'rawData' => [],
            'consentStatus' => ConsentStatus::OptedIn,
        ]);

        $I->runSymfonyConsoleCommand(self::COMMAND, [
            '--externalIdentifier' => self::EMAIL,
        ]);

        $I->seeMessageInConsoleOutput(self::EMAIL);
        $I->seeMessageInConsoleOutput('Dispatching import events');
        $I->seeMessageInConsoleOutput('Done');
    }
}
