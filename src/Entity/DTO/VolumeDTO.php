<?php

namespace App\Entity\DTO;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints as Assert;

class VolumeDTO implements DataTransferObjectInterface
{
    #[Assert\NotBlank]
    private ?int $id = null;

    #[Assert\NotBlank]
    private ?string $hostPath = null;

    private ?string $containerPath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getHostPath(): ?string
    {
        return $this->hostPath;
    }

    public function setHostPath(?string $hostPath)
    {
        $this->hostPath = $hostPath;
    }

    public function getContainerPath(): ?string
    {
        return $this->containerPath;
    }

    public function setContainerPath(?string $containerPath): void
    {
        $this->containerPath = $containerPath;
    }

    #[ArrayShape([
        'hostPath' => 'string',
        'containerPath' => 'string',
    ])]
    public function toArray(): array
    {
        return [
            'hostPath' => $this->hostPath,
            'containerPath' => $this->containerPath,
        ];
    }
}
