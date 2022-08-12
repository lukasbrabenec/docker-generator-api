<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     * @Groups({"default"})
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     * @Groups({"default"})
     */
    private string $name;

    /**
     * @ORM\Column(name="code", type="string", length=256, nullable=false)
     * @Groups({"default"})
     */
    private string $code;

    /**
     * @ORM\Column(name="dockerfile_location", type="string", length=128, nullable=true)
     */
    private ?string $dockerfileLocation;

    /**
     * @OA\Property(ref=@Model(type=ImageGroup::class))
     * @ORM\ManyToOne (targetEntity="ImageGroup")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @ORM\OrderBy({"name" = "ASC"})
     * @Groups({"default"})
     */
    private ImageGroup $group;

    /**
     * @ORM\OneToMany(targetEntity="ImageVersion", mappedBy="image")
     * @Groups({"detail"})
     */
    private Collection $imageVersions;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getDockerfileLocation(): ?string
    {
        return $this->dockerfileLocation;
    }

    public function setDockerfileLocation(?string $dockerfileLocation): void
    {
        $this->dockerfileLocation = $dockerfileLocation;
    }

    public function getGroup(): ImageGroup
    {
        return $this->group;
    }

    public function setGroup(ImageGroup $group): void
    {
        $this->group = $group;
    }

    public function getImageVersions(): Collection
    {
        return $this->imageVersions;
    }

    public function setImageVersions(Collection $imageVersions): void
    {
        $this->imageVersions = $imageVersions;
    }
}
