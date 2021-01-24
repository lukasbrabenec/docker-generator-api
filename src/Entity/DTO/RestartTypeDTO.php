<?php

namespace App\Entity\DTO;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints as Assert;

class RestartTypeDTO implements DataTransferObjectInterface
{
    #[Assert\NotBlank]
    private int $id;

    private string $type;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    #[ArrayShape([
        'id' => 'int',
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
        ];
    }
}
