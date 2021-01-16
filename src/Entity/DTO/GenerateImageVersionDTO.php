<?php

namespace App\Entity\DTO;

use App\Validator\Constraints\ImageVersion;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ImageVersion
 */
class GenerateImageVersionDTO
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $imageVersionId;

    /**
     * @var string|null
     */
    private ?string $version;

    /**
     * @var string|null
     */
    private ?string $imageName;

    /**
     * @var string|null
     */
    private ?string $imageCode;

    /**
     * @var string|null
     */
    private ?string $dockerfileLocation;

    /**
     * @var string|null
     */
    private ?string $dockerfileText = null;

    /**
     * @var array
     */
    private array $extensions;

    /**
     * @var array
     */
    private array $environments;

    /**
     * @var array
     */
    private array $volumes;

    /**
     * @var array
     */
    private array $ports;

    /**
     * @var bool
     */
    private bool $exposePort = false;

    /**
     * @return int
     */
    public function getImageVersionId()
    {
        return $this->imageVersionId;
    }

    /**
     * @param int $imageVersionId
     */
    public function setImageVersionId(int $imageVersionId)
    {
        $this->imageVersionId = $imageVersionId;
    }

    /**
     * @return array
     */
    public function getEnvironments()
    {
        return $this->environments;
    }

    /**
     * @param array $environments
     */
    public function setEnvironments(array $environments)
    {
        $this->environments = $environments;
    }

    /**
     * @param GenerateEnvironmentDTO $environment
     */
    public function addEnvironment(GenerateEnvironmentDTO $environment)
    {
        $this->environments[] = $environment;
    }

    /**
     * @return string
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param string|null $version
     */
    public function setVersion(?string $version): void
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

    /**
     * @param string|null $imageName
     */
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    /**
     * @return string
     */
    public function getImageCode(): ?string
    {
        return $this->imageCode;
    }

    /**
     * @param string|null $imageCode
     */
    public function setImageCode(?string $imageCode): void
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

    /**
     * @param string|null $dockerfileLocation
     */
    public function setDockerfileLocation(?string $dockerfileLocation): void
    {
        $this->dockerfileLocation = $dockerfileLocation;
    }

    /**
     * @return string|null
     */
    public function getDockerfileText(): ?string
    {
        return $this->dockerfileText;
    }

    /**
     * @param string|null $dockerfileText
     */
    public function setDockerfileText(?string $dockerfileText): void
    {
        $this->dockerfileText = $dockerfileText;
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     */
    public function setExtensions(array $extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * @return array
     */
    public function getVolumes(): ?array
    {
        return $this->volumes;
    }

    /**
     * @param array $volumes
     */
    public function setVolumes(array $volumes): void
    {
        $this->volumes = $volumes;
    }

    /**
     * @return array
     */
    public function getPorts(): ?array
    {
        return $this->ports;
    }

    /**
     * @param array $ports
     */
    public function setPorts(array $ports): void
    {
        $this->ports = $ports;
    }

    /**
     * @return bool
     */
    public function isExposePort(): bool
    {
        return $this->exposePort;
    }

    /**
     * @param bool $exposePort
     */
    public function setExposePort(bool $exposePort): void
    {
        $this->exposePort = $exposePort;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [
            'id' => $this->imageVersionId,
            'version' => $this->version,
            'imageName' => $this->imageName,
            'imageCode' => $this->imageCode,
            'dockerfileLocation' => $this->dockerfileLocation
        ];

        foreach ($this->environments as $environment) {
            $array['environments'][] = $environment->toArray();
        }
        /** @var GenerateExtensionDTO $extension */
        foreach ($this->extensions as $extension) {
            if ($extension->isSpecial()) {
                $array['extensions']['special'][] = [
                    'name' => $extension->getName(),
                    'config' => $extension->getConfig()
                ];
            } else {
                $array['extensions']['system'][] = [
                    'name' => $extension->getName(),
                    'config' => $extension->getConfig()
                ];
            }
        }
        foreach ($this->volumes as $volume) {
            $array['volumes'][] = $volume;
        }
        foreach ($this->ports as $port) {
            if (isset($port['exposeToHost']) && $port['exposeToHost']) {
                $this->setExposePort(true);
            }
            $array['ports'][] = $port;
        }
        $array['exposeToHost'] = $this->isExposePort();

        return $array;
    }
}