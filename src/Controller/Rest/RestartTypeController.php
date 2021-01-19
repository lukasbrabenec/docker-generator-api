<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\RestartTypeRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class RestartTypeController extends BaseController
{
    /**
     * @Rest\Route(
     *     "/restart-types",
     *     methods={"GET"},
     *     requirements={"version"="(v1)"}
     * )
     *
     * @param RestartTypeRepository $restartTypeRepository
     * @return ApiResponse
     * @throws ExceptionInterface
     */
    public function list(RestartTypeRepository $restartTypeRepository): ApiResponse
    {
        $data = $this->normalize($restartTypeRepository->findAll(), ['default']);
        return new ApiResponse($data);
    }
}