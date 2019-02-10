<?php
namespace gfabrizi\PlainSimpleFramework\Responses;

use gfabrizi\PlainSimpleFramework\Mappers\Collection;

class JsonResponse implements ResponseInterface
{
    public function __construct($data, int $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json;charset=utf-8');
        if ($data) {
            $output = $this->manageResponse($data);
            echo $output;
        }
    }

    /**
     * Manages the response output
     *
     * @param $data
     * @return false|string
     */
    private function manageResponse($data)
    {
        if ($data instanceof Collection) {
            $outputArr = [];
            foreach ($data as $row) {
                $outputArr[] = $row;
            }
            $output = json_encode($outputArr, JSON_PRETTY_PRINT);
        } else {
            $output = json_encode($data, JSON_PRETTY_PRINT);
        }
        return $output;
    }
}