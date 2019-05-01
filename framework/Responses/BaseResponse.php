<?php
namespace gfabrizi\PlainSimpleFramework\Responses;

abstract class BaseResponse implements ResponseInterface
{
    protected function send(string $output, int $responseCode): void
    {
        http_response_code($responseCode);
        echo $output;
    }

    protected function sendHeader(string $header): void
    {
        if (false === headers_sent()) {
            header($header);
        }
    }
}