<?php

namespace App\Controller\Rest;

use App\Entity\Image;
use App\Http\ApiResponse;
use App\Repository\ImageRepository;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends BaseController
{
    #[Route('/images', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function list(ImageRepository $imageRepository): ApiResponse
    {
        $data = $this->normalize($imageRepository->findAll(), ['default']);

        return new ApiResponse($data);
    }

    #[Route('/images/{imageID}', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function detail(int $imageID, ImageRepository $imageRepository): ApiResponse
    {
        $data = $this->normalize($this->getEntityById($imageRepository, $imageID), ['default', 'detail']);
        $data = $this->extractExtensions([$data]);

        return new ApiResponse($data);
    }

    /**
     * @param Image[] $images
     *
     * @return Image[]
     */
    private function extractExtensions(array $images): array
    {
        foreach ($images as &$image) {
            foreach ($image['imageVersions'] as &$imageVersion) {
                foreach ($imageVersion['extensions'] as $extensionIndex => $extension) {
                    $imageVersion['extensions'][$extensionIndex]['id'] = $extension['extension']['id'];
                    $imageVersion['extensions'][$extensionIndex]['name'] = $extension['extension']['name'];
                    $imageVersion['extensions'][$extensionIndex]['special'] = $extension['extension']['special'];
                    unset($imageVersion['extensions'][$extensionIndex]['extension']);
                }
            }
        }

        return $images;
    }
}
