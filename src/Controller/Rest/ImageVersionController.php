<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ImageVersionRepository;
use FOS\RestBundle\Controller\Annotations as Rest;

class ImageVersionController extends BaseController
{
    /**
     * @Rest\Route(
     *     "/imageversions/{imageVersionID}",
     *     methods={"GET"},
     *     requirements={"version"="(v1)"}
     * )
     *
     * @param int $imageVersionID
     * @param ImageVersionRepository $imageVersionRepository
     * @return ApiResponse
     */
    public function getImageVersions(int $imageVersionID, ImageVersionRepository $imageVersionRepository): ApiResponse
    {
        $data = $this->normalize($this->getEntityById($imageVersionRepository, $imageVersionID));
        return new ApiResponse($data);
    }
}