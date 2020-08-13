<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ImageVersion extends Constraint
{
    public $imageVersionMissing = 'ImageVersion {{ imageVersionId }} does not exist';
    public $extensionMissing = 'Extension {{ extensionId }} does not exist for ImageVersion {{ imageVersionId }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}