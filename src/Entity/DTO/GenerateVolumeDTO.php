<?php

namespace App\Entity\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class GenerateVolumeDTO
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private string $hostPath;

    /**
     * @var string
     */
    private string $containerPath;

    /**
     * @var bool
     */
    private bool $active = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getHostPath(): string
    {
        return $this->hostPath;
    }

    /**
     * @param string $hostPath
     */
    public function setHostPath(string $hostPath)
    {
        $this->hostPath = $hostPath;
    }

    /**
     * @return string
     */
    public function getContainerPath(): string
    {
        return $this->containerPath;
    }

    /**
     * @param string $containerPath
     */
    public function setContainerPath(string $containerPath): void
    {
        $this->containerPath = $containerPath;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'hostPath' => $this->hostPath,
            'containerPath' => $this->containerPath,
            'active' => $this->active,
        ];
    }
}