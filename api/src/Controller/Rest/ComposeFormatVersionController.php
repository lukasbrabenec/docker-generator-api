<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ComposeFormatVersionRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

class ComposeFormatVersionController extends BaseController
{
    /**
     * @var ComposeFormatVersionRepository
     */
    private ComposeFormatVersionRepository $composeFormatVersionRepository;

    /**
     * @param ComposeFormatVersionRepository $composeFormatVersionRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ComposeFormatVersionRepository $composeFormatVersionRepository,
        SerializerInterface $serializer
    ) {
        $this->composeFormatVersionRepository = $composeFormatVersionRepository;
        parent::__construct($serializer);
    }

    /**
     * @Rest\Get("/versions")
     *
     * @return ApiResponse
     */
    public function list()
    {
        $data = $this->normalize($this->composeFormatVersionRepository->findAllAndOrderBy(['composeVersion' => 'DESC']));
        return new ApiResponse($data);
    }
}