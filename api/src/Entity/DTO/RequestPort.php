<?php

namespace App\Entity\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class RequestPort
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
    private bool $exposeToHost = false;

    /**
     * @return int
     */
    public function getId()
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
    public function getInward()
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
    public function isExposeToHost(): bool
    {
        return $this->exposeToHost;
    }

    /**
     * @param bool $exposeToHost
     */
    public function setExposeToHost(bool $exposeToHost): void
    {
        $this->exposeToHost = $exposeToHost;
    }
}