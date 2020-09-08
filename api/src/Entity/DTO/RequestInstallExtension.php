<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RequestInstallExtension
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    private int $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $config;

    /**
     * @var boolean
     */
    private bool $phpExtension;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getConfig(): ?string
    {
        return $this->config;
    }

    /**
     * @param string $config
     */
    public function setConfig(string $config): void
    {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function isPhpExtension(): ?bool
    {
        return $this->phpExtension;
    }

    /**
     * @param bool $phpExtension
     */
    public function setPhpExtension(bool $phpExtension): void
    {
        $this->phpExtension = $phpExtension;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'config' => $this->config,
            'phpExtension' => $this->phpExtension
        ];
    }
}