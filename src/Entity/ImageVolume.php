<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image_volume")
 */
class ImageVolume
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
     * @var string
     *
     * @ORM\Column(name="host_path", type="string", length=255, nullable=false)
     * @Groups({"default"})
     */
    private string $hostPath;

    /**
     * @var string
     *
     * @ORM\Column(name="container_path", type="string", length=255, nullable=false)
     * @Groups({"default"})
     */
    private string $containerPath;

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
     * @return string
     */
    public function getHostPath(): string
    {
        return $this->hostPath;
    }

    /**
     * @param string $hostPath
     *
     * @return ImageVolume
     */
    public function setHostPath(string $hostPath): self
    {
        $this->hostPath = $hostPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getContainerPath(): string
    {
        return $this->containerPath;
    }

    /**
     * @param string $containerPath
     *
     * @return ImageVolume
     */
    public function setContainerPath(string $containerPath): self
    {
        $this->containerPath = $containerPath;

        return $this;
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
