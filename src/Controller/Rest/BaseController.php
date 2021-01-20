<?php

namespace App\Controller\Rest;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BaseController extends AbstractController
{
    private NormalizerInterface $normalizer;

    /**
     * @throws ExceptionInterface
     */
    protected function normalize(mixed $data, array $groups): array
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

    protected function getJSON(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new HttpException(400, 'invalid json');
        }
        return $data;
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
