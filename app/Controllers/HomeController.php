<?php
namespace App\Controllers;

use gfabrizi\PlainSimpleFramework\Http\RequestInterface;
use gfabrizi\PlainSimpleFramework\Responses\Response;
use gfabrizi\PlainSimpleFramework\Responses\ResponseInterface;

class HomeController
{
    public function __construct()
    {
    }

    public function index(RequestInterface $request): ResponseInterface
    {
        $content = '<h1>Hello World!</h1><div>I\'m just a humble test application</div>';
        return new Response('Index', compact('content'));
    }

}