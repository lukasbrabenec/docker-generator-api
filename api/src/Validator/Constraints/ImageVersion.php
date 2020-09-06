<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ImageVersion extends Constraint
{
    public $imageVersionMissing = 'ImageVersion ID {{ imageVersionId }} does not exist';
    public $badExtension = 'Extension ID {{ extensionId }} does not exist for ImageVersion {{ imageVersionId }}';
    public $badEnvironment = 'Environment {{ environmentId }} does not exist for ImageVersion {{ imageVersionId }}';
    public $missingRequiredEnvironment = 'Required environment `{{ environmentCode }}`, ID {{ environmentId }} missing for ImageVersion {{ imageVersionId }}';
    public $badPort = 'Port ID {{ portId }} does not exist for ImageVersion {{ imageVersionId }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}