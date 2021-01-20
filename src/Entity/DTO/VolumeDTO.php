<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class VolumeDTO implements DataTransferObjectInterface
{
    /**
     * @Assert\NotBlank()
     */
    private int $id;

    /**
     * @Assert\NotBlank()
     */
    private string $hostPath;

    private string $containerPath;

    private bool $active = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getHostPath(): string
    {
        return $this->hostPath;
    }

    public function setHostPath(string $hostPath)
    {
        $this->hostPath = $hostPath;
    }

    public function getContainerPath(): string
    {
        return $this->containerPath;
    }

    public function setContainerPath(string $containerPath): void
    {
        $this->containerPath = $containerPath;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function toArray(): array
    {
        return [
            'hostPath' => $this->hostPath,
            'containerPath' => $this->containerPath,
            'active' => $this->active,
        ];
    }
}
