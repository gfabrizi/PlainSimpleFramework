<?php
namespace gfabrizi\PlainSimpleFramework\Tests;

use gfabrizi\PlainSimpleFramework\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testItCanManageGetParameters()
    {
        $_GET['foo'] = 'bar';
        $_GET['Lorem'] = 'Ipsum';
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/dashboard']);

        $this->assertEquals(['foo' => 'bar', 'Lorem' => 'Ipsum'], $request->getBody());
    }

    public function testItCanManagePostParameters()
    {
        $_POST['Dolor'] = 'Sit amet';
        $_POST['Lorem'] = 'Ipsum';
        $request = new Request();
        $request->setHeaders(['request_method' => 'POST', 'request_uri' => '/dashboard']);

        $this->assertEquals(['Dolor' => 'Sit amet', 'Lorem' => 'Ipsum'], $request->getBody());
    }
}