<?php

namespace App\Entity\DTO;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints as Assert;

class PortDTO implements DataTransferObjectInterface
{
    #[Assert\NotBlank]
    private int $id;

    private ?int $inward = 0;

    #[Assert\NotBlank]
    private ?int $outward = 0;

    private bool $exposedToContainers = true;

    private bool $exposedToHost = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getInward(): ?int
    {
        return $this->inward;
    }

    public function setInward(?int $inward): void
    {
        $this->inward = $inward;
    }

    public function getOutward(): ?int
    {
        return $this->outward;
    }

    public function setOutward(?int $outward): void
    {
        $this->outward = $outward;
    }

    public function isExposedToContainers(): bool
    {
        return $this->exposedToContainers;
    }

    public function setExposedToContainers(bool $exposedToContainers): void
    {
        $this->exposedToContainers = $exposedToContainers;
    }

    public function isExposedToHost(): bool
    {
        return $this->exposedToHost;
    }

    public function setExposedToHost(bool $exposedToHost): void
    {
        $this->exposedToHost = $exposedToHost;
    }

    #[ArrayShape([
        'inward' => 'int',
        'outward' => 'int',
        'exposedToContainers' => 'bool',
        'exposedToHost' => 'bool',
    ])]
    public function toArray(): array
    {
        return [
            'inward' => $this->inward,
            'outward' => $this->outward,
            'exposedToContainers' => $this->exposedToContainers,
            'exposedToHost' => $this->exposedToHost,
        ];
    }
}
