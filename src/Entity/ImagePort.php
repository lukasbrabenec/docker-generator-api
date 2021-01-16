<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image_port")
 */
class ImagePort
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @var int
     *
     * @ORM\Column(name="inward", type="integer", nullable=false)
     * @Groups({"default"})
     */
    private int $inward;

    /**
     * @var int
     *
     * @ORM\Column(name="outward", type="integer", nullable=false)
     * @Groups({"default"})
     */
    private int $outward;

    /**
     * @var ImageVersion
     *
     * @ORM\ManyToOne(targetEntity="ImageVersion", cascade={"all"})
     * @ORM\JoinColumn(name="image_version_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private ImageVersion $imageVersion;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return self
     */
    public function setInward(int $inward): self
    {
        $this->inward = $inward;
        return $this;
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
     * @return ImageVersion
     */
    public function getImageVersion(): ImageVersion
    {
        return $this->imageVersion;
    }

    /**
     * @param ImageVersion $imageVersion
     *
     * @return self
     */
    public function setImageVersion(ImageVersion $imageVersion): self
    {
        $this->imageVersion = $imageVersion;

        return $this;
    }
}
