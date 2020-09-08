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
     * @Assert\NotBlank()
     */
    private int $inward;

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
}