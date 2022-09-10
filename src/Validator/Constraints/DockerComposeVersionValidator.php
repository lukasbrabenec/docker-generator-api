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
    public function __construct(private readonly ComposeFormatVersionRepository $composeFormatVersionRepository)
    {
    }

    public function validate(mixed $dockerComposeVersionDTOId, Constraint $constraint): void
    {
        if (!$constraint instanceof DockerComposeVersionConstraint) {
            throw new UnexpectedTypeException($constraint, DockerComposeVersionConstraint::class);
        }

        if ($dockerComposeVersionDTOId === null || $dockerComposeVersionDTOId === '') {
            $this->context->buildViolation($constraint->notBlank)->addViolation();

            return;
        }

        if (!\is_int($dockerComposeVersionDTOId)) {
            throw new UnexpectedValueException($dockerComposeVersionDTOId, 'integer');
        }

        $dockerComposeVersion = $this->getComposeFormatVersionRepository()->find($dockerComposeVersionDTOId);

        if ($dockerComposeVersion === null) {
            $this->context->buildViolation($constraint->dockerComposeVersionNotExist)
                ->setParameter('{{ dockerComposeVersionID }}', $dockerComposeVersionDTOId)
                ->addViolation();
        }
    }

    public function getComposeFormatVersionRepository(): ComposeFormatVersionRepository
    {
        return $this->composeFormatVersionRepository;
    }
}
