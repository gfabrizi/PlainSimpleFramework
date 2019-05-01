<?php
namespace gfabrizi\PlainSimpleFramework\Responses;

use gfabrizi\PlainSimpleFramework\Mappers\Collection;

class JsonResponse extends BaseResponse
{
    public function __construct($data, int $code = 200)
    {
        $this->sendHeader('Content-Type: application/json;charset=utf-8');
        if ($data) {
            $this->send($this->manageResponse($data), $code);
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