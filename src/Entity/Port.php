<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="port")
 */
class Port
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(name="inward", type="integer", nullable=false)
     * @Groups({"default"})
     */
    private int $inward;

    /**
     * @ORM\Column(name="outward", type="integer", nullable=false)
     * @Groups({"default"})
     */
    private int $outward;

    /**
     * @Groups({"default"})
     */
    private bool $exposedToHost = false;

    /**
     * @Groups({"default"})
     */
    private bool $exposedToContainers = true;

    /**
     * @ORM\ManyToOne(targetEntity="ImageVersion", cascade={"all"})
     * @ORM\JoinColumn(name="image_version_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ImageVersion $imageVersion;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInward(): int
    {
        return $this->inward;
    }

    public function setInward(int $inward): self
    {
        $this->inward = $inward;

        return $this;
    }

    public function getOutward(): int
    {
        return $this->outward;
    }

    public function setOutward(int $outward): void
    {
        $this->outward = $outward;
    }

    public function getImageVersion(): ImageVersion
    {
        return $this->imageVersion;
    }

    public function setImageVersion(ImageVersion $imageVersion): self
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }

    public function getExposedToHost(): ?bool
    {
        return $this->exposedToHost;
    }

    public function setExposedToHost(bool $exposedToHost): self
    {
        $this->exposedToHost = $exposedToHost;

        return $this;
    }

    public function getExposedToContainers(): bool
    {
        return $this->exposedToContainers;
    }

    public function setExposedToContainers(bool $exposedToContainers): self
    {
        $this->exposedToContainers = $exposedToContainers;

        return $this;
    }
}
