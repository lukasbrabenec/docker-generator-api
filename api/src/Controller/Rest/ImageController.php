<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ImageRepository;
use FOS\RestBundle\Controller\Annotations as Rest;

class ImageController extends BaseController
{
    /**
     * @Rest\Route(
     *     "/images",
     *     methods={"GET"},
     *     requirements={"version"="(v1)"}
     * )
     *
     * @param ImageRepository $imageRepository
     * @return ApiResponse
     */
    public function list(ImageRepository $imageRepository)
    {
        $data = $this->normalize($imageRepository->findAll());
        return new ApiResponse($data);
    }
}