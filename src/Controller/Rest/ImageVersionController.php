<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ImageVersionRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

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
     * @throws ExceptionInterface
     */
    public function getImageVersions(int $imageVersionID, ImageVersionRepository $imageVersionRepository): ApiResponse
    {
        $data = $this->normalize($this->getEntityById($imageVersionRepository, $imageVersionID), ['default']);
        return new ApiResponse($data);
    }
}