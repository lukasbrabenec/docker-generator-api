<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class RequestImage
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $imageVersionId;

    /**
     *
     * @var ArrayCollection
     */
    private $environment;

    /**
     * RequestImage constructor.
     */
    public function __construct()
    {
        $this->environment = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getImageVersionId()
    {
        return $this->imageVersionId;
    }

    /**
     * @param int $imageVersionId
     */
    public function setImageVersionId($imageVersionId)
    {
        $this->imageVersionId = $imageVersionId;
    }

    /**
     * @return ArrayCollection
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param ArrayCollection $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}