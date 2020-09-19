<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GeneralExceptionNormalizer implements NormalizerInterface
{

    /**
     * @param mixed $exception
     * @param string|null $format
     * @param array $context
     *
     * @return array
     */
    public function normalize($exception, string $format = null, array $context = [])
    {
        return [
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace()
        ];
    }

    /**
     * @param mixed $data
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null)
    {
        return $data instanceof \Throwable;
    }
}