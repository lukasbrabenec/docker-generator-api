<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ComposeFormatVersionRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ComposeFormatVersionController extends BaseController
{
    /**
     * @Rest\Route(
     *     "/versions",
     *     methods={"GET"},
     *     requirements={"version"="(v1)"}
     * )
     *
     * @throws ExceptionInterface
     */
    public function list(ComposeFormatVersionRepository $composeFormatVersionRepository): ApiResponse
    {
        $data = $this->normalize($composeFormatVersionRepository->findAllAndOrderBy(
            ['composeVersion' => 'DESC']
        ), ['default']);

        return new ApiResponse($data);
    }
}
