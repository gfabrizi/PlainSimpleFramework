<?php
namespace gfabrizi\PlainSimpleFramework\Tests;

use gfabrizi\PlainSimpleFramework\Container\Container;
use gfabrizi\PlainSimpleFramework\Container\NotFoundException;
use gfabrizi\PlainSimpleFramework\Tests\stubs\TestDependencyUnsolved;
use gfabrizi\PlainSimpleFramework\Tests\stubs\TestModelLogger;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testItHasModel(): void
    {
        $container = new Container();
        $hasLogger = $container->has(TestModelLogger::class);

        $this->assertTrue($hasLogger);
    }

    public function testItHasntModel(): void
    {
        $container = new Container();
        $hasInexistentClass = $container->has(InexistentClass::class);

        $this->assertFalse($hasInexistentClass);
    }

    public function testItCanResolveDependency(): void
    {
        $container = new Container();
        $logger = $container->get(TestModelLogger::class);
        $output = $logger->doLog("Lorem ipsum dolor sit amet");

        $this->assertEquals('doLog(): Lorem ipsum dolor sit amet', $output);
    }

    public function testItThrowsErrorOnUnsolvedDependency(): void
    {
        $this->expectException(NotFoundException::class);

        $container = new Container();
        $instance = $container->get(TestDependencyUnsolved::class);
    }
}