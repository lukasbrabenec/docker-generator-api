<?php

namespace App\Controller\Rest;

use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\GenerateEnvironmentDTO;
use App\Entity\DTO\GenerateImageVersionDTO;
use App\Entity\DTO\GenerateExtensionDTO;
use App\Entity\DTO\GeneratePortDTO;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use App\Entity\ImageVolume;
use App\Exception\FormException;
use App\Form\GenerateFormType;
use App\Repository\ComposeFormatVersionRepository;
use App\Repository\EnvironmentRepository;
use App\Repository\ImageVersionExtensionRepository;
use App\Repository\ImageVersionRepository;
use App\Service\GeneratorService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GenerateController extends BaseController
{
    /**
     * @var ComposeFormatVersionRepository
     */
    private ComposeFormatVersionRepository $composeFormatVersionRepository;

    /**
     * @var ImageVersionRepository
     */
    private ImageVersionRepository $imageVersionRepository;

    /**
     * @var ImageVersionExtensionRepository
     */
    private ImageVersionExtensionRepository $imageVersionExtensionRepository;

    /**
     * @var EnvironmentRepository
     */
    private EnvironmentRepository $environmentRepository;

    /**
     * @param ComposeFormatVersionRepository $composeFormatVersionRepository
     * @param ImageVersionRepository $imageVersionRepository
     * @param ImageVersionExtensionRepository $imageVersionExtensionRepository
     * @param EnvironmentRepository $environmentRepository
     */
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

    /**
     * @Rest\Route(
     *     "/generate",
     *     methods={"POST"},
     *     requirements={"version"="(v1)"}
     * )
     * @param Request $request
     * @param GeneratorService $generatorService
     * @return BinaryFileResponse
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postGenerate(Request $request, GeneratorService $generatorService)
    {
        /** @var GenerateDTO $requestObject */
        $requestObject = $this->_processForm($request, GenerateFormType::class);
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

    /**
     * @param Request $request
     * @param string $formClass
     * @return mixed
     */
    protected function _processForm(Request $request, string $formClass): GenerateDTO
    {
        $form = $this->createForm($formClass);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->_mergeDTOWithEntities($form->getData());
        } else {
            throw new FormException($form);
        }
        return $data;
    }

    /**
     * @param GenerateDTO $requestDTO
     * @return GenerateDTO
     */
    private function _mergeDTOWithEntities(GenerateDTO $requestDTO): GenerateDTO
    {
        $composeVersion = $this->composeFormatVersionRepository->find($requestDTO->getDockerVersionId());
        $requestDTO->setDockerComposeVersion($composeVersion->getComposeVersion());

        /** @var GenerateImageVersionDTO $imageVersionDTO */
        foreach ($requestDTO->getImageVersions() as $imageVersionDTO) {
            $imageVersion = $this->imageVersionRepository->find($imageVersionDTO->getImageVersionId());
            $this->_mergeImageVersion($imageVersion, $imageVersionDTO);
            $this->_mergePorts($imageVersion, $imageVersionDTO);
            $this->_mergeVolumes($imageVersion, $imageVersionDTO);
            $this->_mergeExtensions($imageVersion, $imageVersionDTO);
            $this->_mergeEnvironments($imageVersionDTO);
        }
        return $requestDTO;
    }

    /**
     * @param ImageVersion $imageVersion
     * @param GenerateImageVersionDTO $imageVersionDTO
     */
    private function _mergeImageVersion(ImageVersion $imageVersion, GenerateImageVersionDTO $imageVersionDTO): void
    {
        $imageVersionDTO->setVersion($imageVersion->getVersion());
        $imageVersionDTO->setImageName($imageVersion->getImage()->getName());
        $imageVersionDTO->setImageCode($imageVersion->getImage()->getCode());
        $imageVersionDTO->setDockerfileLocation($imageVersion->getImage()->getDockerfileLocation());
    }

    /**
     * @param ImageVersion $imageVersion
     * @param GenerateImageVersionDTO $imageVersionDTO
     */
    private function _mergePorts(ImageVersion $imageVersion, GenerateImageVersionDTO $imageVersionDTO): void
    {
        $portsDTO = [];
        // set all ports for image version
        /** @var ImagePort $port */
        foreach ($imageVersion->getPorts() as $port) {
            $portsDTO[$port->getId()] = [
                'inward' => $port->getInward(),
                'outward' => $port->getOutward()
            ];
        }
        // change specific port when user requested
        /** @var GeneratePortDTO $portDTO */
        foreach ($imageVersionDTO->getPorts() as $portDTO) {
            $portsDTO[$portDTO->getId()]['inward'] = $portDTO->isExposeToHost() ? $portDTO->getInward() : $portsDTO[$portDTO->getId()]['inward'];
            $portsDTO[$portDTO->getId()]['outward'] = $portDTO->getOutward();
            $portsDTO[$portDTO->getId()]['exposeToHost'] = $portDTO->isExposeToHost();
        }
        $imageVersionDTO->setPorts($portsDTO);
    }

    /**
     * @param ImageVersion $imageVersion
     * @param GenerateImageVersionDTO $imageVersionDTO
     */
    private function _mergeVolumes(ImageVersion $imageVersion, GenerateImageVersionDTO $imageVersionDTO)
    {
        $volumesDTO = [];
        // set all volumes for image version
        /** @var ImageVolume $volume */
        foreach ($imageVersion->getVolumes() as $volume) {
            $volumesDTO[] = [
                'hostPath' => $volume->getHostPath(),
                'containerPath' => $volume->getContainerPath()
            ];
        }
        $imageVersionDTO->setVolumes($volumesDTO);
    }

    /**
     * @param ImageVersion $imageVersion
     * @param GenerateImageVersionDTO $imageVersionDTO
     */
    private function _mergeExtensions(ImageVersion $imageVersion, GenerateImageVersionDTO $imageVersionDTO): void
    {
        // set all user requested extensions
        /** @var GenerateExtensionDTO $extensionDTO */
        foreach ($imageVersionDTO->getExtensions() as $extensionDTO) {
            $imageVersionExtension = $this->imageVersionExtensionRepository->findOneBy(
                [
                    'extension' => $extensionDTO->getId(),
                    'imageVersion' => $imageVersion
                ]
            );
            $extensionDTO->setName($imageVersionExtension->getExtension()->getName());
            $extensionDTO->setConfig($imageVersionExtension->getConfig());
            $extensionDTO->setSpecial($imageVersionExtension->getExtension()->isSpecial());
        }
    }

    /**
     * @param GenerateImageVersionDTO $imageVersionDTO
     */
    private function _mergeEnvironments(GenerateImageVersionDTO $imageVersionDTO): void
    {
        /** @var GenerateEnvironmentDTO $environmentDTO */
        foreach ($imageVersionDTO->getEnvironments() as $environmentDTO) {
            $environment = $this->environmentRepository->find($environmentDTO->getId());
            $environmentDTO->setCode($environment->getCode());
        }
    }
}