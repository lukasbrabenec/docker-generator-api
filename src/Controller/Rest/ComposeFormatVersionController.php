<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ComposeFormatVersionRepository;
use Symfony\Component\Routing\Annotation\Route;

class ComposeFormatVersionController extends BaseController
{
    #[Route('/versions', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function list(ComposeFormatVersionRepository $composeFormatVersionRepository): ApiResponse
    {
        $data = $this->normalize($composeFormatVersionRepository->findAllAndOrderBy(
            ['composeVersion' => 'DESC']
        ), ['default']);

        return new ApiResponse($data);
    }
}
