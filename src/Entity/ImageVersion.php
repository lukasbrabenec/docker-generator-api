<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image_version")
 */
class ImageVersion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(name="version", type="string", length=128, nullable=false)
     * @Groups({"default"})
     */
    private string $version;

    /**
     * @ORM\ManyToOne(targetEntity="Image", cascade={"all"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Image $image;

    /**
     * @OA\Property(type="array", @OA\Items(ref=@Model(type=Extension::class)))
     * @ORM\OneToMany(targetEntity="ImageVersionExtension", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $extensions;

    /**
     * @ORM\OneToMany(targetEntity="Environment", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $environments;

    /**
     * @ORM\OneToMany(targetEntity="Volume", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $volumes;

    /**
     * @ORM\OneToMany(targetEntity="Port", mappedBy="imageVersion", cascade={"all"})
     * @Groups({"default"})
     */
    private Collection $ports;

    /**
     * @var int[]
     * @Groups({"default"})
     */
    private array $dependsOn = [];

    #[Pure]
    public function __construct()
    {
        $this->extensions = new ArrayCollection();
        $this->environments = new ArrayCollection();
        $this->volumes = new ArrayCollection();
        $this->ports = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getExtensions(): Collection
    {
        return $this->extensions;
    }

    /**
     * @return ImageVersion
     */
    public function setExtensions(Collection $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function addExtension(Extension $extension): void
    {
        $this->extensions->add($extension);
    }

    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    /**
     * @return ImageVersion
     */
    public function setEnvironments(Collection $environments): self
    {
        $this->environments = $environments;

        return $this;
    }

    public function addEnvironment(Environment $environment): void
    {
        $this->environments->add($environment);
    }

    public function getVolumes(): Collection
    {
        return $this->volumes;
    }

    public function setVolumes(Collection $volumes): void
    {
        $this->volumes = $volumes;
    }

    public function addVolume(Volume $volume): void
    {
        $this->volumes->add($volume);
    }

    public function getPorts(): Collection
    {
        return $this->ports;
    }

    public function setPorts(Collection $ports): void
    {
        $this->ports = $ports;
    }

    public function addPort(Port $port): void
    {
        $this->ports->add($port);
    }

    /**
     * @return int[]
     */
    public function getDependsOn(): array
    {
        return $this->dependsOn;
    }

    /**
     * @param int[] $dependsOn
     */
    public function setDependsOn(array $dependsOn): void
    {
        $this->dependsOn = $dependsOn;
    }
}
