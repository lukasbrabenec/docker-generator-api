<?php

namespace App\Validator\Constraints;

use App\Repository\ComposeFormatVersionRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DockerComposeVersionValidator extends ConstraintValidator
{
    /**
     * @var ComposeFormatVersionRepository
     */
    private ComposeFormatVersionRepository $composeFormatVersionRepository;

    /**
     * @param ComposeFormatVersionRepository $composeFormatVersionRepository
     */
    public function __construct(ComposeFormatVersionRepository $composeFormatVersionRepository)
    {
        $this->composeFormatVersionRepository = $composeFormatVersionRepository;
    }

    /**
     * @param mixed $dockerComposeVersionDTOId
     * @param Constraint $constraint
     */
    public function validate($dockerComposeVersionDTOId, Constraint $constraint)
    {
        if (null === $dockerComposeVersionDTOId || '' === $dockerComposeVersionDTOId) {
            return;
        }

        if (!is_integer($dockerComposeVersionDTOId)) {
            throw new UnexpectedValueException($dockerComposeVersionDTOId, 'integer');
        }

        $dockerComposeVersion = $this->getComposeFormatVersionRepository()->find($dockerComposeVersionDTOId);

        if (!is_object($dockerComposeVersion)) {
            $this->context->buildViolation($constraint->dockerComposeVersionNotExist)
                ->setParameter('{{ dockerComposeVersionId }}', $dockerComposeVersionDTOId)
                ->addViolation();
        }
    }

    /**
     * @return ComposeFormatVersionRepository
     */
    public function getComposeFormatVersionRepository(): ComposeFormatVersionRepository
    {
        return $this->composeFormatVersionRepository;
    }
}