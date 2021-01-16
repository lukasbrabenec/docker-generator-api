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
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * @var ImageVersion
     *
     * @ORM\ManyToOne(targetEntity="ImageVersion", inversedBy="extensions")
     * @ORM\JoinColumn(name="image_version_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ImageVersion $imageVersion;

    /**
     * @var Extension
     *
     * @ORM\ManyToOne(targetEntity="Extension")
     * @ORM\JoinColumn(name="extension_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Groups({"default"})
     *
     */
    private Extension $extension;

    /**
     * @var string|null
     *
     * @ORM\Column(name="config", type="string", length=128, nullable=true)
     */
    private ?string $config;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return ImageVersion
     */
    public function getImageVersion(): ImageVersion
    {
        return $this->imageVersion;
    }

    /**
     * @param ImageVersion $imageVersion
     */
    public function setImageVersion(ImageVersion $imageVersion): void
    {
        $this->imageVersion = $imageVersion;
    }

    /**
     * @return Extension
     */
    public function getExtension(): Extension
    {
        return $this->extension;
    }

    /**
     * @param Extension $extension
     */
    public function setExtension(Extension $extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @return string|null
     */
    public function getConfig(): ?string
    {
        return $this->config;
    }

    /**
     * @param string|null $config
     */
    public function setConfig(?string $config): void
    {
        $this->config = $config;
    }
}