<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends JsonResponse
{
    /**
     * @param null $data
     * @param array $errors
     * @param int $status
     * @param array $headers
     * @param bool $json
     */
    public function __construct($data = null, array $errors = [], int $status = Response::HTTP_OK, array $headers = [], bool $json = false)
    {
        parent::__construct($this->format($data, $errors, $status), $status, $headers, $json);
    }

    /**
     * @param null $data
     * @param array $errors
     * @param int $status
     * @return array
     */
    private function format($data = null, $errors = [], $status = Response::HTTP_OK)
    {
        if ($data === null) {
            $data = new \ArrayObject();
        }
        $message = Response::$statusTexts[$status];

        $response = [
            'message' => $message,
            'data' => $data,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return $response;
    }
}