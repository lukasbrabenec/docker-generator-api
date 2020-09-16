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
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @var float
     * @ORM\Column(name="compose_version", type="decimal", nullable=false, precision=3, scale=1)
     * @Groups({"default"})
     */
    private float $composeVersion;

    /**
     * @var string
     * @ORM\Column(name="docker_engine_release", type="string", nullable=false)
     * @Groups({"default"})
     */
    private string $dockerEngineRelease;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getComposeVersion(): float
    {
        return $this->composeVersion;
    }

    /**
     * @param float $composeVersion
     */
    public function setComposeVersion(float $composeVersion): void
    {
        $this->composeVersion = $composeVersion;
    }

    /**
     * @return string
     */
    public function getDockerEngineRelease(): string
    {
        return $this->dockerEngineRelease;
    }

    /**
     * @param string $dockerEngineRelease
     */
    public function setDockerEngineRelease(string $dockerEngineRelease): void
    {
        $this->dockerEngineRelease = $dockerEngineRelease;
    }
}