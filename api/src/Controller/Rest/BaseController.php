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
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $data
     * @return array
     */
    protected function normalize($data): array
    {
        return $this->serializer->normalize($data, null, ['groups' => ['default']]);
    }

    /**
     * @param ServiceEntityRepository $repository
     * @param int $id
     * @return object
     */
    protected function getEntityById(ServiceEntityRepository $repository, $id): object
    {
        $entity = $repository->find($id);
        if (!is_object($entity)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, sprintf('Entity %d not found.', $id));
        }
        return $entity;
    }
}