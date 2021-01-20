<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="compose_format_version")
 */
class ComposeFormatVersion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(name="compose_version", type="decimal", nullable=false, precision=3, scale=1)
     * @Groups({"default"})
     */
    private float $composeVersion;

    /**
     * @ORM\Column(name="docker_engine_release", type="string", nullable=false)
     * @Groups({"default"})
     */
    private string $dockerEngineRelease;

    public function getId(): int
    {
        return $this->id;
    }

    public function getComposeVersion(): float
    {
        return $this->composeVersion;
    }

    public function setComposeVersion(float $composeVersion): void
    {
        $this->composeVersion = $composeVersion;
    }

    public function getDockerEngineRelease(): string
    {
        return $this->dockerEngineRelease;
    }

    public function setDockerEngineRelease(string $dockerEngineRelease): void
    {
        $this->dockerEngineRelease = $dockerEngineRelease;
    }
}
