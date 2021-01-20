<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DockerComposeVersion extends Constraint
{
    public string $dockerComposeVersionNotExist = 'DockerComposeVersion ID {{ dockerComposeVersionId }} does not exist';
}
