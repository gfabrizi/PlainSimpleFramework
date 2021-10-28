<?php
namespace gfabrizi\PlainSimpleFramework\Tests\stubs;

use Exception;
use gfabrizi\PlainSimpleFramework\Http\RequestInterface;
use gfabrizi\PlainSimpleFramework\Responses\JsonResponse;
use gfabrizi\PlainSimpleFramework\Responses\Response;
use gfabrizi\PlainSimpleFramework\Responses\ResponseInterface;
use JsonException;

final class TestController
{
    public function text(RequestInterface $request): void
    {
        echo 'Text outputted from a Controller@Action';
    }

    /**
     * @throws JsonException
     */
    public function jsonResponseArray(RequestInterface $request): ResponseInterface
    {
        $data = [
            'some' => 'data',
            'json' => 'response'
        ];
        return new JsonResponse($data);
    }

    /**
     * @throws Exception
     */
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