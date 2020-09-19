<?php

namespace App\Entity\DTO;

use App\Validator\Constraints\DockerComposeVersion;
use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    private string $projectName;

    /**
     * @var int
     * @DockerComposeVersion()
     */
    private int $dockerVersionId;

    /**
     * @var float
     */
    private float $dockerComposeVersion;

    /**
     *
     * @var array
     *
     * @Assert\Valid()
     *
     */
    private array $imageVersions;

    /**
     * @var string|null
     */
    private ?string $dockerComposeText = null;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->imageVersions = [];
    }

    /**
     * @return string
     */
    public function getProjectName(): string
    {
        return $this->projectName;
    }

    /**
     * @param string $projectName
     */
    public function setProjectName(string $projectName): void
    {
        $this->projectName = $projectName;
    }

    /**
     * @return int
     */
    public function getDockerVersionId()
    {
        return $this->dockerVersionId;
    }

    /**
     * @param int $dockerVersionId
     */
    public function setDockerVersionId(int $dockerVersionId)
    {
        $this->dockerVersionId = $dockerVersionId;
    }

    /**
     * @return float
     */
    public function getDockerComposeVersion(): float
    {
        return $this->dockerComposeVersion;
    }

    /**
     * @param float $dockerComposeVersion
     */
    public function setDockerComposeVersion(float $dockerComposeVersion): void
    {
        $this->dockerComposeVersion = $dockerComposeVersion;
    }

    /**
     * @return array
     */
    public function getImageVersions()
    {
        return $this->imageVersions;
    }

    /**
     * @param array $imageVersions
     */
    public function setImageVersions(array $imageVersions)
    {
        $this->imageVersions = $imageVersions;
    }

    /**
     * @param RequestImageVersion $image
     */
    public function addImage(RequestImageVersion $image)
    {
        $this->imageVersions[] = $image;
    }

    /**
     * @return string
     */
    public function getDockerComposeText(): ?string
    {
        return $this->dockerComposeText;
    }

    /**
     * @param string|null $dockerComposeText
     */
    public function setDockerComposeText(?string $dockerComposeText): void
    {
        $this->dockerComposeText = $dockerComposeText;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [
            'dockerVersion' => $this->dockerComposeVersion,
            'dockerComposeText' => $this->dockerComposeText
        ];

        foreach ($this->getImageVersions() as $imageVersion) {
            $array['imageVersions'][] = $imageVersion->toArray();
        }

        return $array;
    }
}
