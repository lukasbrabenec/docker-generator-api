<?php

namespace App\EventListener;

use App\Serializer\NormalizerCollection;
use App\Http\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\VarDumper\VarDumper;
use Throwable;

class ExceptionListener
{
    /**
     * @var NormalizerCollection
     */
    private NormalizerCollection $normalizerFactory;

    /**
     * @var string
     */
    private string $environment;

    /**
     * @param NormalizerCollection $normalizerFactory
     * @param string $environment
     */
    public function __construct(NormalizerCollection $normalizerFactory, string $environment)
    {
        $this->normalizerFactory = $normalizerFactory;
        $this->environment = $environment;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        $response = $this->createApiResponse($throwable);
        $event->setResponse($response);
    }

    /**
     * @param Throwable $throwable
     * @return ApiResponse
     */
    private function createApiResponse(Throwable $throwable)
    {
        $normalizer = $this->normalizerFactory->getNormalizer($throwable);
        $statusCode = $throwable instanceof HttpExceptionInterface ? $throwable->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        try {
            if ($this->environment === 'dev') {
                $errors = $normalizer ? $normalizer->normalize($throwable) : [];
            } else {
                $errors = [$throwable->getMessage()];
            }
        } catch (ExceptionInterface $e) {
            $errors = [];
        }

        return new ApiResponse(null, $errors, $statusCode);
    }
}