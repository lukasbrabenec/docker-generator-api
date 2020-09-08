<?php

namespace App\Controller\Rest;

use App\Entity\DTO\RequestEnvironment;
use App\Entity\DTO\RequestImageVersion;
use App\Entity\DTO\RequestInstallExtension;
use App\Entity\DTO\RequestPort;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use App\Entity\ImageVolume;
use App\Exception\FormException;
use App\Form\RequestFormType;
use App\Repository\EnvironmentRepository;
use App\Repository\ImageVersionExtensionRepository;
use App\Repository\ImageVersionRepository;
use App\Service\GeneratorService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GenerateController extends BaseController
{
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
     * @param ImageVersionRepository $imageVersionRepository
     * @param ImageVersionExtensionRepository $imageVersionExtensionRepository
     * @param EnvironmentRepository $environmentRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ImageVersionRepository $imageVersionRepository,
        ImageVersionExtensionRepository $imageVersionExtensionRepository,
        EnvironmentRepository $environmentRepository,
        SerializerInterface $serializer
    ) {
        $this->imageVersionRepository = $imageVersionRepository;
        $this->imageVersionExtensionRepository = $imageVersionExtensionRepository;
        $this->environmentRepository = $environmentRepository;
        parent::__construct($serializer);
    }

    /**
     * @Rest\Post("/generate")
     * @param Request $request
     * @param GeneratorService $generatorService
     * @return BinaryFileResponse
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postGenerate(Request $request, GeneratorService $generatorService)
    {
        /** @var \App\Entity\DTO\Request $requestObject */
        $requestObject = $this->_processForm($request, RequestFormType::class);
        $zipFilePath = $generatorService->generate($requestObject);

        $response = new BinaryFileResponse($zipFilePath);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'test.zip'
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
    protected function _processForm(Request $request, string $formClass): \App\Entity\DTO\Request
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
     * @param \App\Entity\DTO\Request $requestDTO
     * @return \App\Entity\DTO\Request
     */
    private function _mergeDTOWithEntities(\App\Entity\DTO\Request $requestDTO): \App\Entity\DTO\Request
    {
        /** @var RequestImageVersion $imageVersionDTO */
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
     * @param RequestImageVersion $imageVersionDTO
     */
    private function _mergeImageVersion(ImageVersion $imageVersion, RequestImageVersion $imageVersionDTO): void
    {
        $imageVersionDTO->setVersion($imageVersion->getVersion());
        $imageVersionDTO->setImageName($imageVersion->getImage()->getName());
        $imageVersionDTO->setImageCode($imageVersion->getImage()->getCode());
        $imageVersionDTO->setDockerfileLocation($imageVersion->getImage()->getDockerfileLocation());
    }

    /**
     * @param ImageVersion $imageVersion
     * @param RequestImageVersion $imageVersionDTO
     */
    private function _mergePorts(ImageVersion $imageVersion, RequestImageVersion $imageVersionDTO): void
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
        /** @var RequestPort $portDTO */
        foreach ($imageVersionDTO->getPorts() as $portDTO) {
            $portsDTO[$portDTO->getId()]['inward'] = $portDTO->getInward();
        }
        $imageVersionDTO->setPorts($portsDTO);
    }

    /**
     * @param ImageVersion $imageVersion
     * @param RequestImageVersion $imageVersionDTO
     */
    private function _mergeVolumes(ImageVersion $imageVersion, RequestImageVersion $imageVersionDTO)
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
     * @param RequestImageVersion $imageVersionDTO
     */
    private function _mergeExtensions(ImageVersion $imageVersion, RequestImageVersion $imageVersionDTO): void
    {
        // set all user requested extensions
        /** @var RequestInstallExtension $extensionDTO */
        foreach ($imageVersionDTO->getInstallExtensions() as $extensionDTO) {
            $imageVersionExtension = $this->imageVersionExtensionRepository->findOneBy(
                [
                    'extension' => $extensionDTO->getId(),
                    'imageVersion' => $imageVersion
                ]
            );
            $extensionDTO->setName($imageVersionExtension->getExtension()->getName());
            $extensionDTO->setConfig($imageVersionExtension->getConfig());
            $extensionDTO->setPhpExtension($imageVersionExtension->getExtension()->isPhpExtension());
        }
    }

    /**
     * @param RequestImageVersion $imageVersionDTO
     */
    private function _mergeEnvironments(RequestImageVersion $imageVersionDTO): void
    {
        /** @var RequestEnvironment $environmentDTO */
        foreach ($imageVersionDTO->getEnvironments() as $environmentDTO) {
            $environment = $this->environmentRepository->find($environmentDTO->getId());
            $environmentDTO->setCode($environment->getCode());
        }
    }
}
