<?php

namespace App\Entity\DTO;

use App\Validator\Constraints\ImageVersion;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints as Assert;

#[ImageVersion]
class ImageVersionDTO implements DataTransferObjectInterface
{
    /**
     * @Assert\NotBlank()
     */
    private ?int $id = null;

    private string $version;

    private ?string $imageName = null;

    private string $imageCode;

    private ?string $dockerfileLocation;

    private ?string $dockerfileText = null;

    /**
     * @var int[]
     */
    private array $otherImageIDsForGeneration = [];

    /**
     * @var ExtensionDTO[]
     */
    private array $extensions;

    /**
     * @var EnvironmentDTO[]
     */
    private array $environments;

    /**
     * @var VolumeDTO[]
     */
    private array $volumes;

    /**
     * @var PortDTO[]
     */
    private array $ports;

    private array $dependsOn = [];

    /**
     * @Assert\NotBlank()
     */
    private RestartTypeDTO $restartType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return EnvironmentDTO[]
     */
    public function getEnvironments(): array
    {
        return $this->environments;
    }

    /**
     * @param EnvironmentDTO $environments
     */
    public function setEnvironments(array $environments)
    {
        $this->environments = $environments;
    }

    public function addEnvironment(EnvironmentDTO $environment)
    {
        $this->environments[] = $environment;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageCode(): string
    {
        return $this->imageCode;
    }

    public function setImageCode(string $imageCode): void
    {
        $this->imageCode = $imageCode;
    }

    /**
     * @return string
     */
    public function getDockerfileLocation(): ?string
    {
        return $this->dockerfileLocation;
    }

    public function setDockerfileLocation(?string $dockerfileLocation): void
    {
        $this->dockerfileLocation = $dockerfileLocation;
    }

    public function getDockerfileText(): ?string
    {
        return $this->dockerfileText;
    }

    public function setDockerfileText(?string $dockerfileText): void
    {
        $this->dockerfileText = $dockerfileText;
    }

    /**
     * @return ExtensionDTO[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param ExtensionDTO[] $extensions
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @return VolumeDTO[]
     */
    public function getVolumes(): ?array
    {
        return $this->volumes;
    }

    /**
     * @param VolumeDTO $volumes
     */
    public function setVolumes(array $volumes): void
    {
        $this->volumes = $volumes;
    }

    /**
     * @return PortDTO[]
     */
    public function getPorts(): ?array
    {
        return $this->ports;
    }

    /**
     * @param PortDTO[] $ports
     */
    public function setPorts(array $ports): void
    {
        $this->ports = $ports;
    }

    public function getRestartType(): RestartTypeDTO
    {
        return $this->restartType;
    }

    public function setRestartType(RestartTypeDTO $restartType): void
    {
        $this->restartType = $restartType;
    }

    public function getDependsOn(): array
    {
        return $this->dependsOn;
    }

    public function setDependsOn(array $dependsOn): void
    {
        $this->dependsOn = $dependsOn;
    }

    /**
     * @return int[]
     */
    public function getOtherImageIDsForGeneration(): array
    {
        return $this->otherImageIDsForGeneration;
    }

    /**
     * @param int[] $otherImageIDsForGeneration
     */
    public function setOtherImageIDsForGeneration(array $otherImageIDsForGeneration): void
    {
        $this->otherImageIDsForGeneration = $otherImageIDsForGeneration;
    }

    #[ArrayShape([
        'id' => 'int',
        'version' => 'null|string',
        'imageName' => 'null|string',
        'imageCode' => 'null|string',
        'dockerfileLocation' => 'null|string',
        'restartType' => 'string',
        'anyPortExposedToContainers' => 'bool',
        'anyPortExposedToHost' => 'bool',
        'dependsOn' => 'string[]',
    ])]
    public function toArray(): array
    {
        $array = [
            'id' => $this->id,
            'version' => $this->version,
            'imageName' => $this->imageName,
            'imageCode' => $this->imageCode,
            'dockerfileLocation' => $this->dockerfileLocation,
            'restartType' => $this->restartType->toArray(),
        ];

        foreach ($this->environments as $environment) {
            $array['environments'][] = $environment->toArray();
        }
        foreach ($this->extensions as $extension) {
            if ($extension->isSpecial()) {
                if ($extension->getCustomCommand()) {
                    $array['extensions']['special']['custom'][] = [
                        'name' => $extension->getName(),
                        'config' => $extension->getConfig(),
                        'customCommand' => $extension->getCustomCommand(),
                    ];
                } else {
                    $array['extensions']['special']['main'][] = [
                        'name' => $extension->getName(),
                        'config' => $extension->getConfig(),
                    ];
                }
            } else {
                if ($extension->getCustomCommand()) {
                    $array['extensions']['system']['custom'][] = [
                        'name' => $extension->getName(),
                        'config' => $extension->getConfig(),
                        'customCommand' => $extension->getCustomCommand(),
                    ];
                } else {
                    $array['extensions']['system']['main'][] = [
                        'name' => $extension->getName(),
                        'config' => $extension->getConfig(),
                    ];
                }
            }
        }
        foreach ($this->volumes as $volume) {
            $array['volumes'][] = $volume->toArray();
        }

        // for easier template generation
        // if there are ports exposed to host - generate ports block
        // if there are ports exposed to containers - generate expose block
        $anyPortExposedToHost = false;
        $anyPortExposedToContainers = false;
        foreach ($this->ports as $port) {
            $array['ports'][] = $port->toArray();
            if ($port->isExposedToHost()) {
                $anyPortExposedToHost = true;
            }
            if ($port->isExposedToContainers()) {
                $anyPortExposedToContainers = true;
            }
        }
        $array['anyPortExposedToHost'] = $anyPortExposedToHost;
        $array['anyPortExposedToContainers'] = $anyPortExposedToContainers;

        foreach ($this->dependsOn as $dependency) {
            $array['dependsOn'][] = $dependency;
        }

        return $array;
    }
}
