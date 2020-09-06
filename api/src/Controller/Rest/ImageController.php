<?php


namespace App\Controller\Rest;


use App\Http\ApiResponse;
use App\Repository\ImageRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

class ImageController extends BaseController
{
    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * @param ImageRepository $imageRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ImageRepository $imageRepository,
        SerializerInterface $serializer
    ) {
        $this->imageRepository = $imageRepository;
        parent::__construct($serializer);
    }

    /**
     * @Rest\Get("/images")
     *
     * @return ApiResponse
     */
    public function list()
    {
        $data = $this->normalize($this->imageRepository->findAll());
        return new ApiResponse($data);
    }
}