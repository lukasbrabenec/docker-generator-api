<?php

namespace App\Controller\Rest;

use App\Entity\ComposeFormatVersion;
use App\Http\ApiResponse;
use App\Repository\ComposeFormatVersionRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ComposeFormatVersionController extends BaseController
{
    /**
     * @Operation(
     *  tags={"Compose format version"},
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
     *    description="Returns all available Compose format versions.",
     *    @SWG\Schema(
     *      allOf={
     *        @SWG\Schema(ref="#/definitions/SuccessResponseModel"),
     *        @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *            property="data",
     *            type="array",
     *            @SWG\Items(ref=@Model(type=ComposeFormatVersion::class, groups={"default"}))
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
    #[Route('/compose-versions', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function list(ComposeFormatVersionRepository $composeFormatVersionRepository): ApiResponse
    {
        $data = $this->normalize($composeFormatVersionRepository->findAllAndOrderBy(
            ['composeVersion' => 'DESC']
        ), ['default']);

        return new ApiResponse($data);
    }
}
