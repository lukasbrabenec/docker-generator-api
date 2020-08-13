<?php

namespace App\Entity\DTO;

use App\Validator\Constraints\ImageVersion;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class RequestImageVersion
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $imageVersionId;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @var string
     */
    private $dockerfileLocation;

    /**
     * @var string
     */
    private $dockerfileText;

    /**
     * @var array
     */
    private $installExtensions;

    /**
     *
     * @var array
     */
    private $environments;

    /**
     * @var array
     */
    private $volumes;

    /**
     * @var array
     */
    private $ports;

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
    public function setImageVersionId($imageVersionId)
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
    public function setEnvironments($environments)
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
    public function getVersion(): string
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
    public function getImageName(): string
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
    public function getDockerfileLocation(): string
    {
        return $this->dockerfileLocation;
    }

    /**
     * @param string $dockerfileLocation
     */
    public function setDockerfileLocation(string $dockerfileLocation): void
    {
        $this->dockerfileLocation = $dockerfileLocation;
    }

    /**
     * @return string
     */
    public function getDockerfileText(): string
    {
        return $this->dockerfileText;
    }

    /**
     * @param string $dockerfileText
     */
    public function setDockerfileText(string $dockerfileText): void
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
    public function setInstallExtensions($installExtensions)
    {
        $this->installExtensions = $installExtensions;
    }

    /**
     * @return array
     */
    public function getVolumes(): array
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
    public function getPorts(): array
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

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new ImageVersion());
    }
}