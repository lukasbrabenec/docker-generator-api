<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

class GeneralExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $exception
     */
    public function normalize($exception, string $format = null, array $context = []): array
    {
        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ];
    }

    /**
     * @param mixed $data
     */
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof Throwable;
    }
}
