<?php

namespace App\Controller\Rest;

use App\Form\RequestFormType;
use App\Repository\EnvironmentRepository;
use App\Repository\ImageVersionExtensionRepository;
use App\Repository\ImageVersionRepository;
use App\Service\DockerfileGenerator;
use App\Service\GeneratorService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class GenerateController extends BaseController
{
    /** @var GeneratorService */
    private $generatorService;

    /**
     * @param ImageVersionRepository $imageVersionRepository
     * @param ImageVersionExtensionRepository $imageVersionExtensionRepository
     * @param EnvironmentRepository $environmentRepository
     * @param GeneratorService $generatorService
     */
    public function __construct(ImageVersionRepository $imageVersionRepository, ImageVersionExtensionRepository $imageVersionExtensionRepository, EnvironmentRepository $environmentRepository, GeneratorService $generatorService)
    {
        $this->generatorService = $generatorService;
        parent::__construct($imageVersionRepository, $imageVersionExtensionRepository, $environmentRepository);
    }

    /**
     * @Rest\Post("/generate")
     * @param Request $request
     * @param DockerfileGenerator $dockerfileGenerator
     * @return BinaryFileResponse
     */
    public function generate(Request $request, DockerfileGenerator $dockerfileGenerator)
    {
        /** @var \App\Entity\DTO\Request $requestObject */
        $requestObject = $this->processForm($request, RequestFormType::class);
        $this->generatorService->generate($requestObject);

        return (new BinaryFileResponse('../files/test.zip'))->deleteFileAfterSend(true);
    }
}
