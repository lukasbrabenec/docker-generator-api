<?php

namespace App\Controller\Rest;

use App\Entity\Image;
use App\Http\ApiResponse;
use App\Repository\ImageRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

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
     * @throws ExceptionInterface
     */
    public function list(ImageRepository $imageRepository): ApiResponse
    {
        $data = $this->normalize($imageRepository->findAll(), ['default']);
        return new ApiResponse($data);
    }

    /**
     * @Rest\Route(
     *     "/images/{imageID}",
     *     methods={"GET"},
     *     requirements={"version"="(v1)"}
     * )
     *
     * @param int $imageID
     * @param ImageRepository $imageRepository
     * @return ApiResponse
     * @throws ExceptionInterface
     */
    public function detail(int $imageID, ImageRepository $imageRepository): ApiResponse
    {
        $data = $this->normalize($this->getEntityById($imageRepository, $imageID), ['default', 'detail']);
        $data = $this->_extractExtensions([$data]);
        return new ApiResponse($data);
    }

    /**
     * @param Image[] $images
     * @return Image[]
     */
    private function _extractExtensions(array $images): array
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