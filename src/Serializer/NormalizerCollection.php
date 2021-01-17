<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Traversable;

class NormalizerCollection
{
    /**
     * @var NormalizerInterface[]
     */
    private array $normalizers;

    /**
     * @param Traversable $normalizers
     */
    public function __construct(iterable $normalizers)
    {
        $this->normalizers = iterator_to_array($normalizers);
    }

    /**
     * @param $data
     * @return NormalizerInterface|null
     */
    public function getNormalizer($data): ?NormalizerInterface
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer instanceof NormalizerInterface && $normalizer->supportsNormalization($data)) {
                return $normalizer;
            }
        }

        return null;
    }
}