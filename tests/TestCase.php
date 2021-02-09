<?php

namespace Tests;

use ConsoleCommander\Kernel;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ConsoleCommander\OutputFormatter\ConsoleOutputFormatter;

abstract class TestCase extends BaseTestCase
{
    protected $kernel;
    protected function setUp(): void
    {
        $this->kernel = new Kernel(
            realpath(__DIR__ . '/Commands'),
            'Tests\Commands',
            new ConsoleOutputFormatter()
        );
        parent::setUp();
    }
}
