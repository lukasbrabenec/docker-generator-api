<?php

namespace App\Entity\DTO;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints as Assert;

class ExtensionDTO implements DataTransferObjectInterface
{
    #[Assert\NotBlank]
    private int $id;

    private string $name;

    private ?string $config;

    private ?string $customCommand = null;

    private bool $special;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getConfig(): ?string
    {
        return $this->config;
    }

    public function setConfig(?string $config): void
    {
        $this->config = $config;
    }

    public function getCustomCommand(): ?string
    {
        return $this->customCommand;
    }

    public function setCustomCommand(?string $customCommand): void
    {
        $this->customCommand = $customCommand;
    }

    public function isSpecial(): ?bool
    {
        return $this->special;
    }

    public function setSpecial(bool $special): void
    {
        $this->special = $special;
    }

    #[ArrayShape([
        'name' => 'string',
        'config' => 'null|string',
        'special' => 'bool',
        'customCommand' => 'null|string',
    ])]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'config' => $this->config,
            'special' => $this->special,
            'customCommand' => $this->customCommand,
        ];
    }
}
