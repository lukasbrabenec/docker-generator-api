<?php

namespace App\Entity\DTO;

use App\Validator\Constraints\DockerComposeVersion;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints as Assert;

class GenerateDTO implements DataTransferObjectInterface
{
    #[Assert\NotBlank]
    private string $projectName;

    #[DockerComposeVersion]
    private ?int $dockerVersionID = null;

    private float $dockerComposeVersion;

    /**
     * @var ImageVersionDTO[]
     */
    #[Assert\Valid]
    private array $imageVersions;

    private ?string $dockerComposeText = null;

    public function __construct()
    {
        $this->imageVersions = [];
    }

    public function getProjectName(): string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): void
    {
        $this->projectName = $projectName;
    }

    public function getDockerVersionID(): ?int
    {
        return $this->dockerVersionID;
    }

    public function setDockerVersionID(int $dockerVersionID): void
    {
        $this->dockerVersionID = $dockerVersionID;
    }

    public function getDockerComposeVersion(): float
    {
        return $this->dockerComposeVersion;
    }

    public function setDockerComposeVersion(float $dockerComposeVersion): void
    {
        $this->dockerComposeVersion = $dockerComposeVersion;
    }

    public function getImageVersions(): array
    {
        return $this->imageVersions;
    }

    public function setImageVersions(array $imageVersions): void
    {
        $this->imageVersions = $imageVersions;
    }

    public function addImage(ImageVersionDTO $image): void
    {
        $this->imageVersions[] = $image;
    }

    public function getDockerComposeText(): ?string
    {
        return $this->dockerComposeText;
    }

    public function setDockerComposeText(?string $dockerComposeText): void
    {
        $this->dockerComposeText = $dockerComposeText;
    }

    #[ArrayShape([
        'dockerVersion' => 'float',
        'dockerComposeText' => 'null|string',
    ])]
    public function toArray(): array
    {
        $array = [
            'dockerVersion' => $this->dockerComposeVersion,
            'dockerComposeText' => $this->dockerComposeText,
        ];

        foreach ($this->getImageVersions() as $imageVersion) {
            $array['imageVersions'][] = $imageVersion->toArray();
        }

        return $array;
    }
}
