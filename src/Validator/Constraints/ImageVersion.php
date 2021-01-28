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
    public string $imageVersionMissing = 'ImageVersion ID {{ imageVersionID }} does not exist.';
    public string $badExtension = 'Extension ID {{ extensionID }} does not exist for ImageVersion {{ imageVersionID }}.';
    public string $emptyProperty = '{{ propertyName }} cannot be empty.';
    public string $badEnvironment = 'Environment {{ environmentID }} does not exist for ImageVersion {{ imageVersionID }}.';
    public string $missingRequiredEnvironment = 'Required environment `{{ environmentCode }}`, ID {{ environmentID }} missing for ImageVersion {{ imageVersionID }}.';
    public string $missingInwardPort = 'Outward port is required when is exposed to other containers.';
    public string $missingInwardAndOutwardPort = 'Inward and outward port is required when is exposed to host.';
    public string $badRestartType = 'Restart Type ID {{ restartTypeID }}, type {{ restartTypeType }} does not exist.';
    public string $badDependency = 'Image ID {{ dependencyID }}, that {{ imageName }} is set to depend on, doesn\'t exist.';
    public string $selfDependency = 'Image {{ imageName }} cannot be dependent on self.';
    public string $dependencyMissing = 'Dependent image {{ dependencyName }} is missing.';
    // @codingStandardsIgnoreEnd

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
