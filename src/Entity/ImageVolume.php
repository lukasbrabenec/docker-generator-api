<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image_volume")
 */
class ImageVolume
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(name="host_path", type="string", length=255, nullable=false)
     * @Groups({"default"})
     */
    private string $hostPath;

    /**
     * @ORM\Column(name="container_path", type="string", length=255, nullable=false)
     * @Groups({"default"})
     */
    private string $containerPath;

    /**
     * @Groups({"default"})
     */
    private bool $active = true;

    /**
     * @ORM\ManyToOne(targetEntity="ImageVersion", cascade={"all"})
     * @ORM\JoinColumn(name="image_version_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ImageVersion $imageVersion;

    public function getId(): int
    {
        return $this->id;
    }

    public function getHostPath(): string
    {
        return $this->hostPath;
    }

    public function setHostPath(string $hostPath): self
    {
        $this->hostPath = $hostPath;

        return $this;
    }

    public function getContainerPath(): string
    {
        return $this->containerPath;
    }

    public function setContainerPath(string $containerPath): self
    {
        $this->containerPath = $containerPath;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getImageVersion(): ImageVersion
    {
        return $this->imageVersion;
    }

    public function setImageVersion(ImageVersion $imageVersion): self
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }
}
