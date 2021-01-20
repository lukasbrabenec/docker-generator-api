<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ImageVersion extends Constraint
{
    // @codingStandardsIgnoreStart
    public string $notBlank = 'This value should not be blank.';
    public string $imageVersionType = 'This value should be of type integer.';
    public string $imageVersionMissing = 'ImageVersion ID {{ imageVersionId }} does not exist';
    public string $badExtension = 'Extension ID {{ extensionId }} does not exist for ImageVersion {{ imageVersionId }}';
    public string $badEnvironment = 'Environment {{ environmentId }} does not exist for ImageVersion {{ imageVersionId }}';
    public string $missingRequiredEnvironment = 'Required environment `{{ environmentCode }}`, ID {{ environmentId }} missing for ImageVersion {{ imageVersionId }}';
    public string $badPort = 'Port ID {{ portId }} does not exist for ImageVersion {{ imageVersionId }}';
    public string $missingInwardPort = 'Inward port is required when is exposed to host';
    public string $missingInwardAndOutwardPort = 'Inward and outward port is required when is exposed to other containers';
    // @codingStandardsIgnoreEnd

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
