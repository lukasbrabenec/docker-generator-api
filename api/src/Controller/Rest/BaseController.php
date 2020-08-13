<?php

namespace App\Controller\Rest;

use App\Entity\DTO\RequestEnvironment;
use App\Entity\DTO\RequestImageVersion;
use App\Entity\DTO\RequestInstallExtension;
use App\Entity\ImagePort;
use App\Entity\ImageVolume;
use App\Exception\FormException;
use App\Repository\EnvironmentRepository;
use App\Repository\ImageVersionExtensionRepository;
use App\Repository\ImageVersionRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractFOSRestController
{
    /**
     * @var ImageVersionRepository
     */
    protected $imageVersionRepository;

    /**
     * @var ImageVersionExtensionRepository
     */
    protected $imageVersionExtensionRepository;

    /**
     * @var EnvironmentRepository
     */
    protected $environmentRepository;

    /**
     * @param ImageVersionRepository $imageVersionRepository
     * @param ImageVersionExtensionRepository $imageVersionExtensionRepository
     * @param EnvironmentRepository $environmentRepository
     */
    public function __construct(ImageVersionRepository $imageVersionRepository, ImageVersionExtensionRepository $imageVersionExtensionRepository, EnvironmentRepository $environmentRepository)
    {
        $this->imageVersionRepository = $imageVersionRepository;
        $this->imageVersionExtensionRepository = $imageVersionExtensionRepository;
        $this->environmentRepository = $environmentRepository;
    }

    /**
     * @param Request $request
     * @param string $formClass
     * @return mixed
     */
    protected function processForm(Request $request, string $formClass)
    {
        $form = $this->createForm($formClass);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->mergeDTOWithEntities($form->getData());
        } else {
            throw new FormException($form);
        }
        return $data;
    }

    /**
     * @param \App\Entity\DTO\Request $requestDTO
     * @return \App\Entity\DTO\Request
     */
    protected function mergeDTOWithEntities(\App\Entity\DTO\Request $requestDTO)
    {
        /** @var RequestImageVersion $imageVersionDTO */
        foreach ($requestDTO->getImageVersions() as $imageVersionDTO) {
            $imageVersion = $this->imageVersionRepository->find($imageVersionDTO->getImageVersionId());
            $imageVersionDTO->setVersion($imageVersion->getVersion());
            $imageVersionDTO->setImageName($imageVersion->getImage()->getName());
            $imageVersionDTO->setDockerfileLocation($imageVersion->getImage()->getDockerfileLocation());

            $portsDTO = [];
            /** @var ImagePort $port */
            foreach ($imageVersion->getPorts() as $port) {
                $portsDTO[] = [
                    'inward' => $port->getInward(),
                    'outward' => $port->getOutward()
                ];
            }
            $imageVersionDTO->setPorts($portsDTO);

            $volumesDTO = [];
            /** @var ImageVolume $volume */
            foreach ($imageVersion->getVolumes() as $volume) {
                $volumesDTO[] = [
                    'hostPath' => $volume->getHostPath(),
                    'containerPath' => $volume->getContainerPath()
                ];
            }
            $imageVersionDTO->setVolumes($volumesDTO);

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

            $requiredEnvironments = $this->environmentRepository->findBy(
                [
                    'imageVersion' => $imageVersionDTO->getImageVersionId(),
                    'required' => true
                ]
            );
            /** @var RequestEnvironment $environmentDTO */
            foreach ($imageVersionDTO->getEnvironments() as $environmentDTO) {
                $environment = $this->environmentRepository->find($environmentDTO->getId());
                $environmentDTO->setCode($environment->getCode());
            }
            foreach ($requiredEnvironments as $requiredEnvironment) {
                $exists = false;
                foreach ($imageVersionDTO->getEnvironments() as $environmentDTO) {
                    if ($environmentDTO->getId() === $requiredEnvironment->getId()) {
                        $exists = true;
                    }
                }
                if (!$exists) {
                    $missingEnvironment = new RequestEnvironment();
                    $missingEnvironment->setId($requiredEnvironment->getId());
                    $missingEnvironment->setCode($requiredEnvironment->getCode());
                    $missingEnvironment->setValue($requiredEnvironment->getDefaultValue());
                    $imageVersionDTO->addEnvironment($missingEnvironment);
                }
            }
        }

        return $requestDTO;
    }
}