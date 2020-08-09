<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class Request
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private $dockerVersion;

    /**
     *
     * @var ArrayCollection
     *
     * @Assert\Valid()
     *
     */
    private $images;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getDockerVersion()
    {
        return $this->dockerVersion;
    }

    /**
     * @param int $dockerVersion
     */
    public function setDockerVersion($dockerVersion)
    {
        $this->dockerVersion = $dockerVersion;
    }

    /**
     * @return Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Collection $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @param RequestImage $image
     */
    public function addImage($image)
    {
        $this->images->add($image);
    }
}
