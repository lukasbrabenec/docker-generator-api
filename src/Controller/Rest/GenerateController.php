<?php

namespace App\Controller\Rest;

use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\ImageVersionDTO;
use App\Entity\ImageVersion;
use App\Exception\FormException;
use App\Form\GenerateFormType;
use App\Repository\ComposeFormatVersionRepository;
use App\Repository\EnvironmentRepository;
use App\Repository\ImageVersionExtensionRepository;
use App\Repository\ImageVersionRepository;
use App\Service\GeneratorService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GenerateController extends BaseController
{
    private ComposeFormatVersionRepository $composeFormatVersionRepository;

    private ImageVersionRepository $imageVersionRepository;

    private ImageVersionExtensionRepository $imageVersionExtensionRepository;

    private EnvironmentRepository $environmentRepository;

    public function __construct(
        ComposeFormatVersionRepository $composeFormatVersionRepository,
        ImageVersionRepository $imageVersionRepository,
        ImageVersionExtensionRepository $imageVersionExtensionRepository,
        EnvironmentRepository $environmentRepository
    ) {
        $this->composeFormatVersionRepository = $composeFormatVersionRepository;
        $this->imageVersionRepository = $imageVersionRepository;
        $this->imageVersionExtensionRepository = $imageVersionExtensionRepository;
        $this->environmentRepository = $environmentRepository;
    }

    #[Route('/generate', requirements: ['version' => 'v1'], methods: ['POST'])]
    public function generate(Request $request, GeneratorService $generatorService): BinaryFileResponse
    {
        $requestObject = $this->processForm($request, GenerateFormType::class);
        $zipFilePath = $generatorService->generate($requestObject);

        $response = new BinaryFileResponse($zipFilePath);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            sprintf('%s.zip', $requestObject->getProjectName())
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->deleteFileAfterSend(true);

        return $response;
    }

    protected function processForm(Request $request, string $formClass): GenerateDTO
    {
        $form = $this->createForm($formClass);
        $form->submit($this->getJSON($request)[$form->getName()]);
        if ($form->isValid()) {
            $data = $this->applyDefaultValues($form->getData());
        } else {
            throw new FormException($form);
        }

        return $data;
    }

    private function applyDefaultValues(GenerateDTO $requestDTO): GenerateDTO
    {
        $composeVersion = $this->composeFormatVersionRepository->find($requestDTO->getDockerVersionId());
        $requestDTO->setDockerComposeVersion($composeVersion->getComposeVersion());

        /** @var ImageVersionDTO $imageVersionDTO */
        foreach ($requestDTO->getImageVersions() as $imageVersionDTO) {
            $imageVersion = $this->imageVersionRepository->find($imageVersionDTO->getImageVersionId());
            $this->mergeImageVersion($imageVersion, $imageVersionDTO);
            $this->mergeExtensions($imageVersion, $imageVersionDTO);
            $this->mergeEnvironments($imageVersionDTO);
        }

        return $requestDTO;
    }

    private function mergeImageVersion(ImageVersion $imageVersion, ImageVersionDTO $imageVersionDTO): void
    {
        $imageVersionDTO->setVersion($imageVersion->getVersion());
        $imageVersionDTO->setImageName($imageVersion->getImage()->getName());
        $imageVersionDTO->setImageCode($imageVersion->getImage()->getCode());
        $imageVersionDTO->setDockerfileLocation($imageVersion->getImage()->getDockerfileLocation());
    }

    private function mergeExtensions(ImageVersion $imageVersion, ImageVersionDTO $imageVersionDTO): void
    {
        // set all user requested extensions
        foreach ($imageVersionDTO->getExtensions() as $extensionDTO) {
            $imageVersionExtension = $this->imageVersionExtensionRepository->findOneBy(
                [
                    'extension' => $extensionDTO->getId(),
                    'imageVersion' => $imageVersion,
                ]
            );
            $extensionDTO->setName($imageVersionExtension->getExtension()->getName());
            $extensionDTO->setConfig($imageVersionExtension->getConfig());
            $extensionDTO->setSpecial($imageVersionExtension->getExtension()->isSpecial());
            $extensionDTO->setCustomCommand($imageVersionExtension->getExtension()->getCustomCommand());
        }
    }

    private function mergeEnvironments(ImageVersionDTO $imageVersionDTO): void
    {
        foreach ($imageVersionDTO->getEnvironments() as $environmentDTO) {
            $environment = $this->environmentRepository->find($environmentDTO->getId());
            $environmentDTO->setCode($environment->getCode());
        }
    }
}
