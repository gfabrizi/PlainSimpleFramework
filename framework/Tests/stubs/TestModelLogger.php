<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

final class TestModelLogger
{
    public function __construct(private TestStringLogger $logger)
    {
    }

    public function doLog($message): string
    {
        return $this->logger->log($message);
    }
}