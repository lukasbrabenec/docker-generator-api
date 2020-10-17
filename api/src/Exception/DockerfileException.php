<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DockerfileException extends HttpException
{
    /**
     * @param string $message
     * @param int $statusCode
     * @param Exception|null $previous
     * @param array $headers
     * @param int|null $code
     */
    public function __construct(string $message = 'Dockerfile generate Failed', int $statusCode = 500, Exception $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}