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
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=false)
     * @Groups({"default"})
     */
    private string $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="special", type="boolean", nullable=true, options={"default": false})
     * @Groups({"default"})
     */
    private bool $special;

    /**
     * @var string|null
     *
     * @ORM\Column(name="custom_command", type="string", nullable=true, options={"default":null})
     */
    private ?string $customCommand;

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
    public function isSpecial(): bool
    {
        return $this->special;
    }

    /**
     * @param bool $special
     * @return Extension
     */
    public function setSpecial(bool $special): self
    {
        $this->special = $special;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomCommand(): string
    {
        return $this->customCommand;
    }

    /**
     * @param string|null $customCommand
     */
    public function setCustomCommand(?string $customCommand): void
    {
        $this->customCommand = $customCommand;
    }
}
