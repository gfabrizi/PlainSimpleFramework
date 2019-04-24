<?php
namespace gfabrizi\PlainSimpleFramework\Tests;

use gfabrizi\PlainSimpleFramework\Http\Request;
use gfabrizi\PlainSimpleFramework\Http\Router;
use gfabrizi\PlainSimpleFramework\Tests\faker\EntityFaker;
use gfabrizi\PlainSimpleFramework\Tests\stubs\TestEntityMapper;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testItReturnsAJsonResponseArray()
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/dashboard']);

        $router = new Router($request);
        $router->get('/dashboard', 'TestController@jsonResponseArray');

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals(
            '{' . PHP_EOL .
            '    "some": "data",' . PHP_EOL .
            '    "json": "response"' . PHP_EOL .
            '}',
            $output
        );
    }

    public function testItReturnsAJsonResponseCollection()
    {
        $mapper = new TestEntityMapper();

        $entity = EntityFaker::getInstance()->make(null, 'Lorem ipsum', 'dolor sit amet');
        $id1 = $mapper->insert($entity);

        $entity = EntityFaker::getInstance()->make(null, 'consectetur adipiscing', 'elit.');
        $id2 = $mapper->insert($entity);

        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/dashboard']);

        $router = new Router($request);
        $router->get('/dashboard', 'TestController@jsonResponseCollection');

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals(
            '[' . PHP_EOL .
            '    {' . PHP_EOL .
            '        "id": ' . $id1 . ',' . PHP_EOL .
            '        "columnName1": "Lorem ipsum",' . PHP_EOL .
            '        "columnName2": "dolor sit amet"' . PHP_EOL .
            '    },' . PHP_EOL .
            '    {' . PHP_EOL .
            '        "id": ' . $id2 . ',' . PHP_EOL .
            '        "columnName1": "consectetur adipiscing",' . PHP_EOL .
            '        "columnName2": "elit."' . PHP_EOL .
            '    }' . PHP_EOL .
            ']',
            $output
        );
    }

    public function testItReturnsAResponseView()
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/lorem-ipsum']);

        $router = new Router($request);
        $router->get('/lorem-ipsum', 'TestController@viewResponse');

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals('Lorem ipsum', $output);
    }
}