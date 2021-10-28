<?php
namespace gfabrizi\PlainSimpleFramework\Responses;

use gfabrizi\PlainSimpleFramework\Mappers\Collection;
use JsonException;

class JsonResponse extends BaseResponse
{
    /**
     * @throws JsonException
     */
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
     * @return bool|string
     * @throws JsonException
     */
    private function manageResponse($data): bool|string
    {
        if ($data instanceof Collection) {
            $outputArr = [];
            foreach ($data as $row) {
                $outputArr[] = $row;
            }
            $output = json_encode($outputArr, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } else {
            $output = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        }
        return $output;
    }
}