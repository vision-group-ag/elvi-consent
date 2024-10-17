<?php

declare(strict_types=1);

namespace Api;

use AppTests\Codeception\Support\ApiTester;

class HealthCheckCest
{
    public function healthCheckIsAlive(ApiTester $I): void
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendGet('/health-check');

        $response = $I->grabResponseJsonData();

        $I->assertIsArray($response);
        $I->assertArrayHasKey('alive', $response);
        $I->assertEquals(true, $response['alive']);
    }
}
