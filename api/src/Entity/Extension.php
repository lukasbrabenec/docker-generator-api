<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="extension")
 */
class Extension
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
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @Groups({"default"})
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="php_extension", type="boolean", nullable=true, options={"default": false})
     */
    private $phpExtension;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPhpExtension(): bool
    {
        return $this->phpExtension;
    }

    /**
     * @param bool $phpExtension
     * @return Extension
     */
    public function setPhpExtension(bool $phpExtension): self
    {
        $this->phpExtension = $phpExtension;

        return $this;
    }
}
