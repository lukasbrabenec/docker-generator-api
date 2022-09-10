<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DockerfileException extends HttpException
{
    public function __construct(
        string $message = 'Dockerfile generate Failed',
        int $statusCode = 500,
        ?\Throwable $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
