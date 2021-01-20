<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image_environment")
 */
class ImageEnvironment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(name="code", type="string", length=128, nullable=false)
     * @Groups({"default"})
     */
    private string $code;

    /**
     * @ORM\Column(name="default_value", type="string", length=256, nullable=true)
     * @Groups({"default"})
     */
    private ?string $defaultValue;

    /**
     * @ORM\ManyToOne(targetEntity="ImageVersion", cascade={"all"})
     * @ORM\JoinColumn(name="image_version_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ImageVersion $imageVersion;

    /**
     * @ORM\Column(name="required", type="boolean", nullable=false, options={"default": false})
     * @Groups({"default"})
     */
    private bool $required;

    /**
     * @ORM\Column(name="hidden", type="boolean", nullable=false, options={"default": false})
     * @Groups({"default"})
     */
    private bool $hidden;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
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

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }
}
