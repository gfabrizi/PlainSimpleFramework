<?php
namespace gfabrizi\PlainSimpleFramework\Http;

interface RequestInterface
{
    public function getBody();

    public function get($property);
}