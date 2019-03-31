<?php
namespace gfabrizi\PlainSimpleFramework\Http;

interface RequestInterface
{
    public function getBody(): array;

    public function get($property);
}