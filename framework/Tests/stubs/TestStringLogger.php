<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

final class TestStringLogger
{
    public function log($message): string
    {
        return "doLog(): " . $message;
    }
}