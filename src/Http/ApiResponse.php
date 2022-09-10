<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends JsonResponse
{
    public function __construct(
        ?array $data = null,
        array $errors = [],
        int $status = Response::HTTP_OK,
        array $headers = [],
        bool $json = false
    ) {
        parent::__construct($this->format($data, $errors, $status), $status, $headers, $json);
    }

    private function format(?array $data = null, array $errors = [], int $status = Response::HTTP_OK): array
    {
        if ($data === null) {
            $data = [];
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
