<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", cascade={"all"})
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $group;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="DockerInstall")
     * @ORM\JoinTable(name="image_install_dependency",
     *     joinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="docker_install_id", referencedColumnName="id")}
     *     )
     */
    private $dockerInstall;

    public function __construct()
    {
        $this->dockerInstall = new ArrayCollection();
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
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group $group
     *
     * @return self
     */
    public function setGroup(Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @param DockerInstall $dockerInstall
     * @return $this
     */
    public function addDockerInstall(DockerInstall $dockerInstall): self
    {
        $this->dockerInstall->add($dockerInstall);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDockerInstall(): ArrayCollection
    {
        return $this->dockerInstall;
    }

    /**
     * @param ArrayCollection $dockerInstall
     * @return Image
     */
    public function setDockerInstall(ArrayCollection $dockerInstall): self
    {
        $this->dockerInstall = $dockerInstall;

        return $this;
    }
}
