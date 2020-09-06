<?php

namespace App\Entity\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class RequestPort
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $inward;

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
    public function setId($id)
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
    public function setInward($inward)
    {
        $this->inward = $inward;
    }
}