<?php

namespace App\Entity\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class GeneratePortDTO
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $id;

    /**
     * @var int
     */
    private int $inward;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $outward;

    /**
     * @var bool
     */
    private bool $exposedToContainers = true;

    /**
     * @var bool
     */
    private bool $exposedToHost = false;

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
     * @return int
     */
    public function getInward(): int
    {
        return $this->inward;
    }

    /**
     * @param int $inward
     */
    public function setInward(int $inward)
    {
        $this->inward = $inward;
    }

    /**
     * @return int
     */
    public function getOutward(): int
    {
        return $this->outward;
    }

    /**
     * @param int $outward
     */
    public function setOutward(int $outward): void
    {
        $this->outward = $outward;
    }

    /**
     * @return bool
     */
    public function isExposedToContainers(): bool
    {
        return $this->exposedToContainers;
    }

    /**
     * @param bool $exposedToContainers
     */
    public function setExposedToContainers(bool $exposedToContainers): void
    {
        $this->exposedToContainers = $exposedToContainers;
    }

    /**
     * @return bool
     */
    public function isExposedToHost(): bool
    {
        return $this->exposedToHost;
    }

    /**
     * @param bool $exposedToHost
     */
    public function setExposedToHost(bool $exposedToHost): void
    {
        $this->exposedToHost = $exposedToHost;
    }

    /**
     * @return array
     */
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