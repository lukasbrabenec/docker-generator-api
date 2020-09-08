<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ImageVersionRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

class ImageVersionController extends BaseController
{
    /**
     * @var ImageVersionRepository
     */
    private ImageVersionRepository $imageVersionRepository;

    /**
     * @param ImageVersionRepository $imageVersionRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ImageVersionRepository $imageVersionRepository,
        SerializerInterface $serializer
    ) {
        $this->imageVersionRepository = $imageVersionRepository;
        parent::__construct($serializer);
    }

    /**
     * @Rest\Get("/imageversions/{imageVersionID}")
     *
     * @param int $imageVersionID
     * @return ApiResponse
     */
    public function getImageVersions(int $imageVersionID)
    {
        $data = $this->normalize($this->getEntityById($this->imageVersionRepository, $imageVersionID));
        return new ApiResponse($data);
    }
}