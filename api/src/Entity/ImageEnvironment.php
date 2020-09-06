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
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=128, nullable=false)
     * @Groups({"default"})
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="default_value", type="string", length=256, nullable=true)
     * @Groups({"default"})
     */
    private $defaultValue;

    /**
     * @var ImageVersion
     *
     * @ORM\ManyToOne(targetEntity="ImageVersion", cascade={"all"})
     * @ORM\JoinColumn(name="image_version_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $imageVersion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="required", type="boolean", nullable=false, options={"default": false})
     * @Groups({"default"})
     */
    private $required;

    /**
     * @var boolean
     *
     * @ORM\Column(name="hidden", type="boolean", nullable=false, options={"default": false})
     * @Groups({"default"})
     */
    private $hidden;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    /**
     * @param string|null $defaultValue
     */
    public function setDefaultValue(?string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
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
     *
     * @return self
     */
    public function setImageVersion(ImageVersion $imageVersion): self
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }
}
