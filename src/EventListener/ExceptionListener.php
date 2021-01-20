<?php

namespace App\EventListener;

use App\Http\ApiResponse;
use App\Serializer\NormalizerCollection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Throwable;

class ExceptionListener
{
    private LoggerInterface $logger;

    private NormalizerCollection $normalizerFactory;

    private string $environment;

    public function __construct(LoggerInterface $logger, NormalizerCollection $normalizerFactory, string $environment)
    {
        $this->logger = $logger;
        $this->normalizerFactory = $normalizerFactory;
        $this->environment = $environment;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        $this->logException($throwable);
        $response = $this->createApiResponse($throwable);
        $event->setResponse($response);
    }

    private function createApiResponse(Throwable $throwable): ApiResponse
    {
        $normalizer = $this->normalizerFactory->getNormalizer($throwable);
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        try {
            if ($throwable instanceof HttpExceptionInterface) {
                $statusCode = $throwable->getStatusCode();
                $errors = $normalizer ? $normalizer->normalize($throwable) : [];
            } else {
                if ('dev' === $this->environment) {
                    $errors = $normalizer ? $normalizer->normalize($throwable) : [];
                } else {
                    $errors = [$throwable->getMessage()];
                }
            }
        } catch (ExceptionInterface $e) {
            $errors = [];
        }

        return new ApiResponse(null, $errors, $statusCode);
    }

    private function logException(Throwable $throwable)
    {
        if ($throwable instanceof HttpExceptionInterface && $throwable->getStatusCode() >= 500) {
            $this->logger->critical($throwable);
        } else {
            $this->logger->critical($throwable);
        }
    }
}
