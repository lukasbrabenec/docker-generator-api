<?php

namespace App\Validator\Constraints;

use App\Entity\DTO\ImageVersionDTO;
use App\Entity\DTO\PortDTO;
use App\Entity\ImageEnvironment;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use App\Entity\ImageVersionExtension;
use App\Validator\Constraints\ImageVersion as ImageVersionConstraint;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ImageVersionValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param mixed $generateImageVersionDTO
     */
    public function validate($generateImageVersionDTO, Constraint $constraint)
    {
        if (!$constraint instanceof ImageVersionConstraint) {
            throw new UnexpectedTypeException($constraint, ImageVersionConstraint::class);
        }
        $imageVersionId = $generateImageVersionDTO->getImageVersionId();

        if (null === $imageVersionId || '' === $imageVersionId) {
            return;
        }

        if (!is_integer($imageVersionId)) {
            throw new UnexpectedValueException($imageVersionId, 'integer');
        }

        /** @var ImageVersion|null $imageVersion */
        $imageVersion = $this->entityManager->getRepository(ImageVersion::class)->find($imageVersionId);
        if (!is_object($imageVersion)) {
            $this->context->buildViolation($constraint->imageVersionMissing)
                ->setParameter('{{ imageVersionId }}', $imageVersionId)
                ->atPath('imageVersionId')
                ->addViolation();

            return;
        }

        // extensions
        $this->validateExtensions($constraint, $generateImageVersionDTO, $imageVersionId);
        // environments
        $this->validateEnvironments($constraint, $generateImageVersionDTO, $imageVersion);
        // ports
        $this->validatePorts($constraint, $generateImageVersionDTO, $imageVersionId);
    }

    private function validateExtensions(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $generateImageVersionDTO,
        int $imageVersionId
    ) {
        foreach ($generateImageVersionDTO->getExtensions() as $installExtensionDTO) {
            $imageDockerInstall = $this->entityManager->getRepository(ImageVersionExtension::class)->findOneBy([
                'extension' => $installExtensionDTO->getId(),
                'imageVersion' => $imageVersionId,
            ]);
            if (!is_object($imageDockerInstall)) {
                $this->context->buildViolation($constraint->badExtension)
                    ->setParameter('{{ extensionId }}', $installExtensionDTO->getId())
                    ->setParameter('{{ imageVersionId }}', $imageVersionId)
                    ->atPath('extensions')
                    ->addViolation();
            }
        }
    }

    private function validateEnvironments(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $generateImageVersionDTO,
        ImageVersion $imageVersion
    ) {
        foreach ($generateImageVersionDTO->getEnvironments() as $environmentDTO) {
            /** @var ImageEnvironment $environment */
            $environment = $this->entityManager->getRepository(ImageEnvironment::class)->find($environmentDTO->getId());
            if (!is_object($environment) || $environment->getImageVersion()->getId() !== $imageVersion->getId()) {
                $this->context->buildViolation($constraint->badEnvironment)
                    ->setParameter('{{ environmentId }}', $environmentDTO->getId())
                    ->setParameter('{{ imageVersionId }}', $imageVersion->getId())
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
                    ->setParameter('{{ environmentId }}', $requiredEnvironment->getId())
                    ->setParameter('{{ imageVersionId }}', $imageVersion->getId())
                    ->atPath('environments')
                    ->addViolation();
            }
        }
    }

    private function validatePorts(
        ImageVersionConstraint $constraint,
        ImageVersionDTO $generateImageVersionDTO,
        int $imageVersionId
    ) {
        /** @var PortDTO $portDTO */
        foreach ($generateImageVersionDTO->getPorts() as $portDTO) {
            /** @var ImagePort $port */
            $port = $this->entityManager->getRepository(ImagePort::class)->find($portDTO->getId());
            if (!is_object($port) || $port->getImageVersion()->getId() !== $imageVersionId) {
                $this->context->buildViolation($constraint->badPort)
                    ->setParameter('{{ portId }}', $portDTO->getId())
                    ->setParameter('{{ imageVersionId }}', $imageVersionId)
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
}
