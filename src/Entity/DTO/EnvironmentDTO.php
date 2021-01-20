<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EnvironmentDTO implements DataTransferObjectInterface
{
    /**
     * @Assert\NotBlank()
     */
    private int $id;

    private string $code;

    /**
     * @Assert\NotBlank()
     */
    private string $value;

    public function getId(): int
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

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->code,
            'value' => $this->value,
        ];
    }
}
