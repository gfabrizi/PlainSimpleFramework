<?php
namespace gfabrizi\PlainSimpleFramework\Tests;

use gfabrizi\PlainSimpleFramework\Http\Request;
use gfabrizi\PlainSimpleFramework\Http\RequestInterface;
use gfabrizi\PlainSimpleFramework\Http\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testItCanMakeAGet(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/']);

        $router = new Router($request);
        $router->get('/', function() { return 'Lorem ipsum dolor sit amet'; });

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals('Lorem ipsum dolor sit amet', $output);
    }

    public function testItCanMakeAPost(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'POST', 'request_uri' => '/users/new']);

        $router = new Router($request);
        $router->post('/users/new', function() { return 'This is a post call on /users/new route'; });

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals('This is a post call on /users/new route', $output);
    }

    public function testItCanMakeAPut(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'PUT', 'request_uri' => '/users/new']);

        $router = new Router($request);
        $router->put('/users/new', function() { return 'This is a put call on /users/new route'; });

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals('This is a put call on /users/new route', $output);
    }

    public function testItCanMakeADelete(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'DELETE', 'request_uri' => '/users/42']);

        $router = new Router($request);
        $router->delete('/users/42', function() { return 'This is a delete call on /users/new route'; });

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals('This is a delete call on /users/new route', $output);
    }

    public function testItCanUseControllerAction(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/dashboard']);

        $router = new Router($request);
        $router->get('/dashboard', 'TestController@text');

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals('Text outputted from a Controller@Action', $output);
    }

    public function testItGives404OnAPostToAGet(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/users']);

        $router = new Router($request);
        $router->post('/users', function() { return 'This should be a 404...'; });

        $router = null;

        $this->assertEquals(404, http_response_code());
    }

    public function testItGives404OnInexistentController(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/users']);

        $router = new Router($request);
        $router->get('/users', 'Inexistent@action');

        $router = null;

        $this->assertEquals(404, http_response_code());
    }

    public function testItSupportVariableSlug(): void
    {
        $request = new Request();
        $request->setHeaders(['request_method' => 'GET', 'request_uri' => '/users/42']);

        $router = new Router($request);
        $router->get(
            '/users/{id}',
            function(RequestInterface $request, int $id) { return 'Info for the user ' . $id; }
        );

        ob_start();
        $router = null;
        $output = ob_get_clean();

        $this->assertEquals('Info for the user 42', $output);
    }
}