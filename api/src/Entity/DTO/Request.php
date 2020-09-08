<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $dockerVersion;

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
     * @return int
     */
    public function getDockerVersion()
    {
        return $this->dockerVersion;
    }

    /**
     * @param int $dockerVersion
     */
    public function setDockerVersion(int $dockerVersion)
    {
        $this->dockerVersion = $dockerVersion;
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
            'dockerVersion' => $this->dockerVersion,
            'dockerComposeText' => $this->dockerComposeText
        ];

        foreach ($this->getImageVersions() as $imageVersion) {
            $array['imageVersions'][] = $imageVersion->toArray();
        }

        return $array;
    }
}
