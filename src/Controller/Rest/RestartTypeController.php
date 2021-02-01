<?php

namespace App\Controller\Rest;

use App\Entity\RestartType;
use App\Http\ApiResponse;
use App\Repository\RestartTypeRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class RestartTypeController extends BaseController
{
    /**
     * @Operation(
     *  tags={"Restart Type"},
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
     *    description="Returns all available restart types.",
     *    @SWG\Schema(
     *      allOf={
     *        @SWG\Schema(ref="#/definitions/SuccessResponseModel"),
     *        @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *            property="data",
     *            type="array",
     *            @SWG\Items(ref=@Model(type=RestartType::class, groups={"default"}))
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
    #[Route('/restart-types', requirements: ['version' => 'v1'], methods: ['GET'])]
    public function list(RestartTypeRepository $restartTypeRepository): ApiResponse
    {
        $data = $this->normalize($restartTypeRepository->findAll(), ['default']);

        return new ApiResponse($data);
    }
}
