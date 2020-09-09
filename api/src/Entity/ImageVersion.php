<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=128, nullable=false)
     * @Groups({"default"})
     */
    private string $version;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="Image", cascade={"all"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Image $image;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ImageVersionExtension", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $extensions;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ImageEnvironment", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $environments;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ImageVolume", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $volumes;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ImagePort", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $ports;

    /**
     * ImageVersion constructor.
     */
    public function __construct()
    {
        $this->extensions = new ArrayCollection();
        $this->environments = new ArrayCollection();
        $this->volumes = new ArrayCollection();
        $this->ports = new ArrayCollection();
    }

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

    /**
     * @return Collection
     */
    public function getExtensions(): Collection
    {
        return $this->extensions;
    }

    /**
     * @param Collection $extensions
     * @return ImageVersion
     */
    public function setExtensions(Collection $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @param Extension $extension
     */
    public function addExtension(Extension $extension): void
    {
        $this->extensions->add($extension);
    }

    /**
     * @return Collection
     */
    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    /**
     * @param Collection $environments
     * @return ImageVersion
     */
    public function setEnvironments(Collection $environments): self
    {
        $this->environments = $environments;

        return $this;
    }

    /**
     * @param ImageEnvironment $environment
     */
    public function addEnvironment(ImageEnvironment $environment): void
    {
        $this->environments->add($environment);
    }

    /**
     * @return Collection
     */
    public function getVolumes(): Collection
    {
        return $this->volumes;
    }

    /**
     * @param Collection $volumes
     */
    public function setVolumes(Collection $volumes): void
    {
        $this->volumes = $volumes;
    }

    /**
     * @param ImageVolume $volume
     */
    public function addVolume(ImageVolume $volume): void
    {
        $this->volumes->add($volume);
    }

    /**
     * @return Collection
     */
    public function getPorts(): Collection
    {
        return $this->ports;
    }

    /**
     * @param Collection $ports
     */
    public function setPorts(Collection $ports): void
    {
        $this->ports = $ports;
    }

    /**
     * @param ImagePort $port
     */
    public function addPort(ImagePort $port): void
    {
        $this->ports->add($port);
    }
}
