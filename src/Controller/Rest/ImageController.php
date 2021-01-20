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
     * @throws ExceptionInterface
     */
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
        foreach ($images as $imageIndex => &$image) {
            foreach ($image['imageVersions'] as $imageVersionIndex => &$imageVersion) {
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
