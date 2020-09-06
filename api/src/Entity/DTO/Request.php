<?php

namespace App\Entity\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $dockerVersion;

    /**
     *
     * @var ArrayCollection
     *
     * @Assert\Valid()
     *
     */
    private $imageVersions;

    /**
     * @var string
     */
    private $dockerComposeText;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->imageVersions = new ArrayCollection();
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
    public function setDockerVersion($dockerVersion)
    {
        $this->dockerVersion = $dockerVersion;
    }

    /**
     * @return Collection
     */
    public function getImageVersions()
    {
        return $this->imageVersions;
    }

    /**
     * @param Collection $imageVersions
     */
    public function setImageVersions($imageVersions)
    {
        $this->imageVersions = $imageVersions;
    }

    /**
     * @param RequestImageVersion $image
     */
    public function addImage($image)
    {
        $this->imageVersions->add($image);
    }

    /**
     * @return string
     */
    public function getDockerComposeText(): ?string
    {
        return $this->dockerComposeText;
    }

    /**
     * @param string $dockerComposeText
     */
    public function setDockerComposeText(string $dockerComposeText): void
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
