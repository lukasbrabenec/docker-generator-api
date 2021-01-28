<?php

namespace App\Entity\DTO;

use JetBrains\PhpStorm\ArrayShape;

class EnvironmentDTO implements DataTransferObjectInterface
{
    private ?int $id = null;

    private string $code;

    private ?string $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value)
    {
        $this->value = $value;
    }

    #[ArrayShape([
        'name' => 'string',
        'value' => 'string',
    ])]
    public function toArray(): array
    {
        return [
            'name' => $this->code,
            'value' => $this->value,
        ];
    }
}
