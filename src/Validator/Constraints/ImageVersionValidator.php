<?php

namespace App\Validator\Constraints;

use App\Entity\DTO\ImageVersionDTO;
use App\Entity\DTO\PortDTO;
use App\Entity\Image;
use App\Entity\ImageEnvironment;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use App\Entity\ImageVersionExtension;
use App\Entity\RestartType;
use App\Validator\Constraints\ImageVersion as ImageVersionConstraint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ImageVersionValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ImageVersionDTO $imageVersionDTO
     */
    public function validate(mixed $imageVersionDTO, Constraint $constraint)
    {
        if (!$constraint instanceof ImageVersionConstraint) {
            throw new UnexpectedTypeException($constraint, ImageVersionConstraint::class);
        }
        $imageVersionId = $imageVersionDTO->getId();

        if (!is_integer($imageVersionId)) {
            $this->context->buildViolation($constraint->imageVersionType)
                ->atPath('imageVersionID')
                ->addViolation();

            return;
        }

        /** @var ImageVersion|null $imageVersion */
        $imageVersion = $this->entityManager->getRepository(ImageVersion::class)->find($imageVersionId);
        if (!is_object($imageVersion)) {
            $this->context->buildViolation($constraint->imageVersionMissing)
                ->setParameter('{{ imageVersionID }}', $imageVersionId)
                ->atPath('imageVersionID')
                ->addViolation();

            return;
        }

        // extensions
        $this->validateExtensions($constraint, $imageVersionDTO, $imageVersionId);
        // environments
        $this->validateEnvironments($constraint, $imageVersionDTO, $imageVersion);
        // ports
        $this->validatePorts($constraint, $imageVersionDTO, $imageVersionId);
        // restart type
        $this->validateRestartType($constraint, $imageVersionDTO);
        // dependencies
        $this->validateDependencies($constraint, $imageVersionDTO, $imageVersion);
    }

    private function validateExtensions(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $generateImageVersionDTO,
        int $imageVersionId,
    ) {
        foreach ($generateImageVersionDTO->getExtensions() as $installExtensionDTO) {
            $imageDockerInstall = $this->entityManager->getRepository(ImageVersionExtension::class)->findOneBy([
                'extension' => $installExtensionDTO->getId(),
                'imageVersion' => $imageVersionId,
            ]);
            if (!is_object($imageDockerInstall)) {
                $this->context->buildViolation($constraint->badExtension)
                    ->setParameter('{{ extensionID }}', $installExtensionDTO->getId())
                    ->setParameter('{{ imageVersionID }}', $imageVersionId)
                    ->atPath('extensions')
                    ->addViolation();
            }
        }
    }

    private function validateEnvironments(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $generateImageVersionDTO,
        ImageVersion $imageVersion,
    ) {
        foreach ($generateImageVersionDTO->getEnvironments() as $environmentDTO) {
            /** @var ImageEnvironment $environment */
            $environment = $this->entityManager->getRepository(ImageEnvironment::class)->find($environmentDTO->getId());
            if (!is_object($environment) || $environment->getImageVersion()->getId() !== $imageVersion->getId()) {
                $this->context->buildViolation($constraint->badEnvironment)
                    ->setParameter('{{ environmentID }}', $environmentDTO->getId())
                    ->setParameter('{{ imageVersionID }}', $imageVersion->getId())
                    ->atPath('environments')
                    ->addViolation();
            }
        }
        $requiredEnvironments = $this->entityManager->getRepository(ImageEnvironment::class)->findBy([
            'imageVersion' => $imageVersion,
            'required' => true,
            'hidden' => false,
        ]);
        /** @var ImageEnvironment $requiredEnvironment */
        foreach ($requiredEnvironments as $requiredEnvironment) {
            $exist = false;
            foreach ($generateImageVersionDTO->getEnvironments() as $environmentDTO) {
                if ($requiredEnvironment->getId() === $environmentDTO->getId()) {
                    $exist = true;
                }
            }
            if (!$exist) {
                $this->context->buildViolation($constraint->missingRequiredEnvironment)
                    ->setParameter('{{ environmentCode }}', $requiredEnvironment->getCode())
                    ->setParameter('{{ environmentID }}', $requiredEnvironment->getId())
                    ->setParameter('{{ imageVersionID }}', $imageVersion->getId())
                    ->atPath('environments')
                    ->addViolation();
            }
        }
    }

    private function validatePorts(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $imageVersionDTO,
        int $imageVersionID,
    ) {
        /** @var PortDTO $portDTO */
        foreach ($imageVersionDTO->getPorts() as $portDTO) {
            /** @var ImagePort $port */
            $port = $this->entityManager->getRepository(ImagePort::class)->find($portDTO->getId());
            if (!is_object($port) || $port->getImageVersion()->getId() !== $imageVersionID) {
                $this->context->buildViolation($constraint->badPort)
                    ->setParameter('{{ portID }}', $portDTO->getId())
                    ->setParameter('{{ imageVersionID }}', $imageVersionID)
                    ->atPath('ports')
                    ->addViolation();
            }
            if ($portDTO->isExposedToHost()
                && !$portDTO->getInward()
                && $portDTO->isExposedToContainers()
                && !$portDTO->getOutward()
            ) {
                $this->context->buildViolation($constraint->missingInwardAndOutwardPort)
                    ->addViolation();
            }
            if ($portDTO->isExposedToHost() && !$portDTO->getInward()) {
                $this->context->buildViolation($constraint->missingInwardPort)
                    ->addViolation();
            }
        }
    }

    private function validateRestartType(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $imageVersionDTO,
    ) {
        $restartType = $this->entityManager->getRepository(RestartType::class)
            ->findOneBy([
                'id' => $imageVersionDTO->getRestartType()->getId(),
                'type' => $imageVersionDTO->getRestartType()->getType(),
            ]);
        if (!is_object($restartType)) {
            $this->context->buildViolation($constraint->badRestartType)
                ->setParameter('{{ restartTypeID }}', $imageVersionDTO->getRestartType()->getId())
                ->setParameter('{{ restartTypeType }}', $imageVersionDTO->getRestartType()->getType())
                ->atPath('restartType')
                ->addViolation();
        }
    }

    private function validateDependencies(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $imageVersionDTO,
        ImageVersion $imageVersion,
    ) {
        foreach ($imageVersionDTO->getDependsOn() as $dependency) {
            if ($dependency === $imageVersion->getImage()->getId()) {
                $this->context->buildViolation($constraint->selfDependency)
                    ->setParameter('{{ imageName }}', $imageVersion->getImage()->getName())
                    ->atPath('dependsOn')
                    ->addViolation();
            }
            /** @var Image $imageDependency */
            $imageDependency = $this->entityManager->getRepository(Image::class)->find($dependency);
            if (!is_object($imageDependency)) {
                $this->context->buildViolation($constraint->badDependency)
                    ->setParameter('{{ dependencyID }}', $dependency)
                    ->setParameter('{{ imageName }}', $imageVersion->getImage()->getName())
                    ->atPath('dependsOn')
                    ->addViolation();
            } elseif (!in_array($imageDependency->getId(), $imageVersionDTO->getOtherImageIDsForGeneration())) {
                $this->context->buildViolation($constraint->dependencyMissing)
                    ->setParameter('{{ dependencyName }}', $imageDependency->getName())
                    ->atPath('dependsOn')
                    ->addViolation();
            }
        }
    }
}
