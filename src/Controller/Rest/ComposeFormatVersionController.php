<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ComposeFormatVersionRepository;
use FOS\RestBundle\Controller\Annotations as Rest;

class ComposeFormatVersionController extends BaseController
{
    /**
     * @Rest\Route(
     *     "/versions",
     *     methods={"GET"},
     *     requirements={"version"="(v1)"}
     * )
     *
     * @param ComposeFormatVersionRepository $composeFormatVersionRepository
     * @return ApiResponse
     */
    public function list(ComposeFormatVersionRepository $composeFormatVersionRepository)
    {
        $data = $this->normalize($composeFormatVersionRepository->findAllAndOrderBy(['composeVersion' => 'DESC']), ['default']);
        return new ApiResponse($data);
    }
}