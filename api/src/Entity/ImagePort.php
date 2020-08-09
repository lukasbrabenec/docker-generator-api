<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="inward", type="integer", nullable=false)
     */
    private $inward;

    /**
     * @var int
     *
     * @ORM\Column(name="outward", type="integer", nullable=false)
     */
    private $outward;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="Image", cascade={"all"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $image;

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
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     *
     * @return self
     */
    public function setImage(Image $image): self
    {
        $this->image = $image;

        return $this;
    }
}
