<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use gfabrizi\PlainSimpleFramework\Http\RequestInterface;
use gfabrizi\PlainSimpleFramework\Responses\JsonResponse;
use gfabrizi\PlainSimpleFramework\Responses\Response;
use gfabrizi\PlainSimpleFramework\Responses\ResponseInterface;

class TestController
{

    public function text(RequestInterface $request): void
    {
        echo 'Text outputted from a Controller@Action';
    }

    public function jsonResponseArray(RequestInterface $request): ResponseInterface
    {
        $data = [
            'some' => 'data',
            'json' => 'response'
        ];
        return new JsonResponse($data);
    }

    public function jsonResponseCollection(RequestInterface $request): ResponseInterface
    {
        $mapper = new TestEntityMapper();
        $entities = $mapper->findAll();

        return new JsonResponse($entities);
    }

    public function viewResponse(RequestInterface $request): ResponseInterface
    {
        $content = 'ipsum';
        return new Response('index', compact('content'));
    }


}