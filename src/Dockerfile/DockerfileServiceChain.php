<?php

namespace App\Dockerfile;

use App\Service\Dockerfile\AbstractDockerfile;

class DockerfileServiceChain
{
    private array $dockerfileServices;

    public function __construct()
    {
        $this->dockerfileServices = [];
    }

    public function addDockerfileService(AbstractDockerfile $dockerfile, $imageName): void
    {
        $this->dockerfileServices[$imageName] = $dockerfile;
    }

    /**
     * @return AbstractDockerfile|void
     */
    public function getDockerfileService(string $imageCode): ?AbstractDockerfile
    {
        if (array_key_exists($imageCode, $this->dockerfileServices)) {
            return $this->dockerfileServices[$imageCode];
        }
    }

    public function hasDockerfileService(string $imageCode): bool
    {
        return array_key_exists($imageCode, $this->dockerfileServices);
    }
}
