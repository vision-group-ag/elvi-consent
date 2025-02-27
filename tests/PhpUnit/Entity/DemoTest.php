<?php

declare(strict_types=1);

namespace AppTests\PhpUnit\Entity;

use App\Entity\DemoEntity;
use PHPUnit\Framework\TestCase;

/**
 * Normally entity should be tested via services/acceptance/api tests, but this is for demo only to test getters and
 * write 1st skeleton test.
 */
class DemoTest extends TestCase
{
    public function test(): void
    {
        $test = new DemoEntity('id', 'name', true);
        $this->assertEquals('id', $test->getId());
        $this->assertEquals('name', $test->getName());
        $this->assertTrue($test->isPublic());
    }
}
