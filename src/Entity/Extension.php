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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @Groups({"default"})
     */
    private string $name;

    /**
     * @ORM\Column(name="special", type="boolean", nullable=true, options={"default": false})
     * @Groups({"default"})
     */
    private bool $special;

    /**
     * @ORM\Column(name="custom_command", type="string", nullable=true, options={"default":null})
     */
    private ?string $customCommand;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isSpecial(): bool
    {
        return $this->special;
    }

    /**
     * @return Extension
     */
    public function setSpecial(bool $special): self
    {
        $this->special = $special;

        return $this;
    }

    public function getCustomCommand(): ?string
    {
        return $this->customCommand;
    }

    public function setCustomCommand(?string $customCommand): void
    {
        $this->customCommand = $customCommand;
    }
}
