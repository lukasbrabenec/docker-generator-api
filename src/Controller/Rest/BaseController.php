<?php

namespace App\Controller\Rest;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BaseController extends AbstractController
{
    private NormalizerInterface $normalizer;

    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @throws ExceptionInterface
     */
    protected function normalize(mixed $data, array $groups): array
    {
        return $this->normalizer->normalize($data, 'json', ['groups' => $groups]);
    }

    protected function getEntityById(ServiceEntityRepository $repository, int $id): object
    {
        $entity = $repository->find($id);

        if ($entity === null) {
            throw new HttpException(Response::HTTP_NOT_FOUND, \sprintf('Entity %d not found.', $id));
        }

        return $entity;
    }

    protected function getJSON(Request $request): array
    {
        try {
            $data = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new HttpException(400, 'invalid json', $e);
        }

        return $data;
    }
}
