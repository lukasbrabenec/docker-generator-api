<?php

namespace App\Controller\Rest;

use App\Entity\Image;
use App\Http\ApiResponse;
use App\Repository\ImageRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends BaseController
{
    /**
     * @Operation(
     *  tags={"Image"},
     *  @SWG\Parameter(
     *    name="version",
     *    required=true,
     *    in="path",
     *    type="string",
     *    enum={"v1"},
     *    description="Version of API endpoint."
     *  ),
     *  @SWG\Response(
     *    response=200,
     *    description="Returns all available images.",
     *    @SWG\Schema(
     *      allOf={
     *        @SWG\Schema(ref="#/definitions/SuccessResponseModel"),
     *        @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *            property="data",
     *            type="array",
     *            @SWG\Items(ref=@Model(type=Image::class, groups={"default"}))
     *          )
     *        )
     *      }
     *    )
     *  ),
     *  @SWG\Response(
     *   response=400,
     *   description="Bad Request.",
     *   @SWG\Schema(
     *     ref="#/definitions/BadRequestErrorModel"
     *   )
     *  ),
     *  @SWG\Response(
     *   response=404,
     *   description="Not Found",
     *   @SWG\Schema(
     *     ref="#/definitions/NotFoundErrorModel"
     *   )
     *  ),
     *  @SWG\Response(
     *   response=500,
     *   description="Internal Server Error.",
     *   @SWG\Schema(
     *     ref="#/definitions/ServerErrorModel"
     *   )
     * )
     * )
     */
    #[Route('/images', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function list(ImageRepository $imageRepository): ApiResponse
    {
        $data = $this->normalize($imageRepository->findAll(), ['default']);

        return new ApiResponse($data);
    }

    /**
     * @Operation(
     *  tags={"Image"},
     *  @SWG\Parameter(
     *    name="version",
     *    required=true,
     *    in="path",
     *    type="string",
     *    enum={"v1"},
     *    description="Version of API endpoint."
     *  ),
     *  @SWG\Parameter(
     *     name="imageID",
     *     required=true,
     *     in="path",
     *     type="integer",
     *     description="Identificator of image."
     *  ),
     *  @SWG\Response(
     *    response=200,
     *    description="Returns image detail.",
     *    @SWG\Schema(
     *      allOf={
     *        @SWG\Schema(ref="#/definitions/SuccessResponseModel"),
     *        @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *            property="data",
     *            type="array",
     *            @SWG\Items(ref="#/definitions/ImageDetail")
     *          )
     *        )
     *      }
     *    )
     *  ),
     *  @SWG\Response(
     *   response=500,
     *   description="Internal Server Error.",
     *   @SWG\Schema(
     *     ref="#/definitions/ServerErrorModel"
     *   )
     * )
     * )
     */
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
