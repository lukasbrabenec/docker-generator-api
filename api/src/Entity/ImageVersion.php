<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image_version")
 */
class ImageVersion
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
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=128, nullable=false)
     */
    private $version;

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
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return self
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;
        return $this;
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
