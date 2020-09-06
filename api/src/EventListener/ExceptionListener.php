<?php

namespace App\EventListener;

use App\Factory\NormalizerFactory;
use App\Http\ApiResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Throwable;

class ExceptionListener
{
    /**
     * @var NormalizerFactory
     */
    private $normalizerFactory;

    /**
     * @var boolean
     */
    private $isDebug;

    /**
     * @param NormalizerFactory $normalizerFactory
     */
    public function __construct(NormalizerFactory $normalizerFactory)
    {
        $this->normalizerFactory = $normalizerFactory;
        $this->isDebug = getenv('APP_ENV') === 'dev';
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        $request = $event->getRequest();

        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $response = $this->createApiResponse($throwable);
            $event->setResponse($response);
        }
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
            if ($this->isDebug) {
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