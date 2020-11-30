<?php

namespace App\Controller\Rest;

use App\Entity\Image;
use App\Http\ApiResponse;
use App\Repository\ImageRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\VarDumper\VarDumper;

class ImageController extends BaseController
{
    /**
     * @Rest\Route(
     *     "/images",
     *     methods={"GET"},
     *     requirements={"version"="(v1)"}
     * )
     *
     * @param ImageRepository $imageRepository
     * @return ApiResponse
     */
    public function list(ImageRepository $imageRepository)
    {
        $data = $this->normalize($imageRepository->findAll());
        $data = $this->_extractExtensions($data);
        return new ApiResponse($data);
    }

    /**
     * @param Image[] $images
     * @return Image[]
     */
    private function _extractExtensions(array $images)
    {
        foreach ($images as $imageIndex => $image) {
            foreach ($image['imageVersions'] as $imageVersionIndex => $imageVersion) {
                foreach ($imageVersion['extensions'] as $extensionIndex => $extension) {
                    $images[$imageIndex]['imageVersions'][$imageVersionIndex]['extensions'][$extensionIndex]['id'] = $extension['extension']['id'];
                    $images[$imageIndex]['imageVersions'][$imageVersionIndex]['extensions'][$extensionIndex]['name'] = $extension['extension']['name'];
                    $images[$imageIndex]['imageVersions'][$imageVersionIndex]['extensions'][$extensionIndex]['special'] = $extension['extension']['special'];
                    unset($images[$imageIndex]['imageVersions'][$imageVersionIndex]['extensions'][$extensionIndex]['extension']);
                }
            }
        }
        return $images;
    }
}