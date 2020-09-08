<?php

namespace App\Entity\DTO;

use App\Validator\Constraints\ImageVersion;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ImageVersion
 */
class RequestImageVersion
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $imageVersionId;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var string
     */
    private string $imageName;

    /**
     * @var string
     */
    private string $imageCode;

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
    private array $installExtensions;

    /**
     *
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
     * @param RequestEnvironment $environment
     */
    public function addEnvironment(RequestEnvironment $environment)
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
     * @param string $version
     */
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

    /**
     * @param string $imageName
     */
    public function setImageName(string $imageName): void
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
     * @param string $imageCode
     */
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
    public function getInstallExtensions()
    {
        return $this->installExtensions;
    }

    /**
     * @param array $installExtensions
     */
    public function setInstallExtensions(array $installExtensions)
    {
        $this->installExtensions = $installExtensions;
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
        /** @var RequestInstallExtension $extension */
        foreach ($this->installExtensions as $extension) {
            if ($extension->isPhpExtension()) {
                if ($extension->getConfig()) {
                    $array['extensions']['php'][]['withConfig']['name'] = $extension->getName();
                    $array['extensions']['php'][]['withConfig']['config'] = $extension->getConfig();
                } else {
                    $array['extensions']['php'][]['withoutConfig']['name'] = $extension->getName();
                    $array['extensions']['php'][]['withoutConfig']['config'] = $extension->getConfig();
                }
            } else {
                $array['extensions']['system'] = $extension;
                if ($extension->getConfig()) {
                    $array['extensions']['system'][]['config'] = $extension->getConfig();
                }
            }
        }
        foreach ($this->volumes as $volume) {
            $array['volumes'][] = $volume;
        }
        foreach ($this->ports as $port) {
            $array['ports'][] = $port;
        }

        return $array;
    }
}