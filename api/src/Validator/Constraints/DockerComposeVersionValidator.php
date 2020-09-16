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
     * @param mixed $dockerComposeVersionId
     * @param Constraint $constraint
     */
    public function validate($dockerComposeVersionId, Constraint $constraint)
    {
        if (null === $dockerComposeVersionId || '' === $dockerComposeVersionId) {
            return;
        }

        if (!is_integer($dockerComposeVersionId)) {
            throw new UnexpectedValueException($dockerComposeVersionId, 'integer');
        }

        $dockerComposeVersion = $this->getComposeFormatVersionRepository()->find($dockerComposeVersionId);

        if (!is_object($dockerComposeVersion)) {
            $this->context->buildViolation($constraint->dockerComposeVersionNotExist)
                ->setParameter('{{ dockerComposeVersionId }}', $dockerComposeVersionId)
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