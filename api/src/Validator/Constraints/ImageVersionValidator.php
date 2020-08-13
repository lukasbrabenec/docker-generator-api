<?php

namespace App\Validator\Constraints;

use App\Entity\DTO\RequestImageVersion;
use App\Entity\ImageDockerInstall;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\VarDumper\VarDumper;

class ImageVersionValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param RequestImageVersion $requestImageVersion
     * @param Constraint $constraint
     */
    public function validate($requestImageVersion, Constraint $constraint)
    {
        if (!$constraint instanceof ImageVersion) {
            throw new UnexpectedTypeException($constraint, ImageVersion::class);
        }
        $imageVersionId = $requestImageVersion->getImageVersionId();

        if (null === $imageVersionId || '' === $imageVersionId) {
            return;
        }

        if (!is_integer($imageVersionId)) {
            throw new UnexpectedValueException($imageVersionId, 'integer');
        }

        $imageVersion = $this->entityManager->getRepository(\App\Entity\ImageVersion::class)->find($imageVersionId);
        if (!is_object($imageVersion)) {
            $this->context->buildViolation($constraint->imageVersionMissing)
                ->setParameter('{{ imageVersionId }}', $imageVersionId)
                ->addViolation();
        }

        // extensions
        foreach ($requestImageVersion->getInstallExtensions() as $installExtensionId) {
            $imageDockerInstall = $this->entityManager->getRepository(ImageDockerInstall::class)->findOneBy(['extension' => $installExtensionId, 'imageVersion' => $imageVersionId]);
            if (!is_object($imageDockerInstall)) {
                $this->context->buildViolation($constraint->extensionMissing)
                    ->setParameter('{{ extensionId }}', $installExtensionId)
                    ->setParameter('{{ imageVersionId }}', $imageVersionId)
                    ->addViolation();
            }
        }
    }
}