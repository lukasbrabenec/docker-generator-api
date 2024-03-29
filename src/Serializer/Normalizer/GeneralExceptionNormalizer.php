<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

class GeneralExceptionNormalizer implements NormalizerInterface
{
    /**
     * @param Throwable $exception
     */
    public function normalize(mixed $exception, ?string $format = null, array $context = []): array
    {
        return [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof Throwable;
    }
}
