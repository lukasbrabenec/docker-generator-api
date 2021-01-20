<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class DockerComposeVersion extends Constraint
{
    public string $notBlank = 'This value should not be blank.';
    public string $dockerComposeVersionNotExist = 'DockerComposeVersion ID {{ dockerComposeVersionId }} does not exist';
}
