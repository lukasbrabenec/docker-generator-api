<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\RestartTypeRepository;
use Symfony\Component\Routing\Annotation\Route;

class RestartTypeController extends BaseController
{
    #[Route('/restart-types', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function list(RestartTypeRepository $restartTypeRepository): ApiResponse
    {
        $data = $this->normalize($restartTypeRepository->findAll(), ['default']);

        return new ApiResponse($data);
    }
}
