<?php

namespace App\Controller\Rest;

use App\Http\ApiResponse;
use App\Repository\ImageVersionRepository;
use Symfony\Component\Routing\Annotation\Route;

class ImageVersionController extends BaseController
{
    #[Route('/image-versions/{imageVersionID}', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function getImageVersions(int $imageVersionID, ImageVersionRepository $imageVersionRepository): ApiResponse
    {
        $data = $this->normalize($this->getEntityById($imageVersionRepository, $imageVersionID), ['default']);

        return new ApiResponse($data);
    }
}
