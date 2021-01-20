<?php

namespace App\Validator\Constraints;

use App\Repository\ComposeFormatVersionRepository;
use App\Validator\Constraints\DockerComposeVersion as DockerComposeVersionConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DockerComposeVersionValidator extends ConstraintValidator
{
    private ComposeFormatVersionRepository $composeFormatVersionRepository;

    public function __construct(ComposeFormatVersionRepository $composeFormatVersionRepository)
    {
        $this->composeFormatVersionRepository = $composeFormatVersionRepository;
    }

    /**
     * @param mixed $dockerComposeVersionDTOId
     */
    public function validate($dockerComposeVersionDTOId, Constraint $constraint)
    {
        if (!$constraint instanceof DockerComposeVersionConstraint) {
            throw new UnexpectedTypeException($constraint, DockerComposeVersionConstraint::class);
        }
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

    public function getComposeFormatVersionRepository(): ComposeFormatVersionRepository
    {
        return $this->composeFormatVersionRepository;
    }
}
