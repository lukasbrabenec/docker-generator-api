<?php


namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image")
 */
class Image
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
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     * @Groups({"default"})
     */
    private string $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=256, nullable=false)
     * @Groups({"default"})
     */
    private string $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dockerfile_location", type="string", length=128, nullable=true)
     */
    private ?string $dockerfileLocation;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="ImageVersion", mappedBy="image")
     * @Groups({"default"})
     */
    private Collection $imageVersions;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDockerfileLocation(): ?string
    {
        return $this->dockerfileLocation;
    }

    /**
     * @param string|null $dockerfileLocation
     */
    public function setDockerfileLocation(?string $dockerfileLocation): void
    {
        $this->dockerfileLocation = $dockerfileLocation;
    }

    /**
     * @return Collection
     */
    public function getImageVersions(): Collection
    {
        return $this->imageVersions;
    }

    /**
     * @param Collection $imageVersions
     */
    public function setImageVersions(Collection $imageVersions): void
    {
        $this->imageVersions = $imageVersions;
    }
}
