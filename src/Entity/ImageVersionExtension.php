<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image_version_extension")
 */
class ImageVersionExtension
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="ImageVersion", inversedBy="extensions")
     * @ORM\JoinColumn(name="image_version_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ImageVersion $imageVersion;

    /**
     * @ORM\ManyToOne(targetEntity="Extension")
     * @ORM\JoinColumn(name="extension_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Groups({"default"})
     */
    private Extension $extension;

    /**
     * @ORM\Column(name="config", type="string", length=128, nullable=true)
     */
    private ?string $config;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getImageVersion(): ImageVersion
    {
        return $this->imageVersion;
    }

    public function setImageVersion(ImageVersion $imageVersion): void
    {
        $this->imageVersion = $imageVersion;
    }

    public function getExtension(): Extension
    {
        return $this->extension;
    }

    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }

    public function getConfig(): ?string
    {
        return $this->config;
    }

    public function setConfig(?string $config): void
    {
        $this->config = $config;
    }
}
