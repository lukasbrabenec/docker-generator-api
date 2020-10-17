<?php

namespace App\Controller\Rest;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;

class BaseController extends AbstractFOSRestController
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param mixed $data
     * @return array
     */
    protected function normalize($data): array
    {
        return $this->_getSerializer()->normalize($data, null, ['groups' => ['default']]);
    }

    /**
     * @param ServiceEntityRepository $repository
     * @param int $id
     * @return object
     */
    protected function getEntityById(ServiceEntityRepository $repository, int $id): object
    {
        $entity = $repository->find($id);
        if (!is_object($entity)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, sprintf('Entity %d not found.', $id));
        }
        return $entity;
    }

    /**
     * @return SerializerInterface
     */
    protected function _getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }
}