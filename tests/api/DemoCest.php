<?php
declare(strict_types=1);

namespace AppTests\api;

use AppTests\_support\ApiTester;

class DemoCest
{
    public function passDemoTest(ApiTester $I): void
    {
        $I->amGoingTo("Check if API Docs is accessible");
        $I->sendGET('/api/docs');
        $I->seeResponseCodeIsSuccessful();
    }
}