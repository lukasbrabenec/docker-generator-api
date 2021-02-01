<?php

namespace App\Controller\Rest;

use App\Entity\DTO\GenerateDTO;
use App\Exception\FormException;
use App\Form\GenerateFormType;
use App\Service\GeneratorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GenerateController extends BaseController
{
    /**
     * @Operation(
     *  tags={"Generate"},
     *  produces={"application/zip", "application/json"},
     *  @SWG\Parameter(
     *    name="version",
     *    required=true,
     *    in="path",
     *    type="string",
     *    enum={"v1"},
     *    description="Version of API endpoint."
     *  ),
     *  @SWG\Parameter(
     *     name="generate",
     *     in="body",
     *     @SWG\Schema(
     *     @Model(type=GenerateFormType::class)
     * )
     *  ),
     *  @SWG\Response(
     *    response=200,
     *    description="Returns ZIP archive with docker-compose.",
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
    #[Route('/generate', requirements: ['version' => 'v1'], methods: ['POST'])]
    public function generate(Request $request, GeneratorService $generatorService): BinaryFileResponse
    {
        $generateDTO = $this->processForm($request, GenerateFormType::class);
        $zipFilePath = $generatorService->generate($generateDTO);

        $response = new BinaryFileResponse($zipFilePath);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            sprintf('%s.zip', $generateDTO->getProjectName())
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->deleteFileAfterSend(true);

        return $response;
    }

    protected function processForm(Request $request, string $formClass): GenerateDTO
    {
        $form = $this->createForm($formClass);
        $form->submit($this->getJSON($request));
        if (!$form->isValid()) {
            throw new FormException($form);
        }

        return $form->getData();
    }
}
