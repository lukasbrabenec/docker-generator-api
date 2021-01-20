<?php

namespace App\Controller\Rest;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BaseController extends AbstractFOSRestController
{
    private NormalizerInterface $normalizer;

    /**
     * @param mixed $data
     *
     * @return array
     * @throws ExceptionInterface
     */
    protected function normalize($data, array $groups): array
    {
        return $this->getNormalizer()->normalize($data, 'json', ['groups' => $groups]);
    }

    protected function getEntityById(ServiceEntityRepository $repository, int $id): object
    {
        $entity = $repository->find($id);
        if (!is_object($entity)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, sprintf('Entity %d not found.', $id));
        }

        return $entity;
    }

    protected function getNormalizer(): NormalizerInterface
    {
        return $this->normalizer;
    }

    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer = $normalizer;
    }
}
