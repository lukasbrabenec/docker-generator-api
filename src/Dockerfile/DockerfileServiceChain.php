<?php

namespace App\Dockerfile;

use App\Service\Dockerfile\AbstractDockerfile;
use JetBrains\PhpStorm\Pure;

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

    #[Pure]
    public function getDockerfileService(string $imageCode): ?AbstractDockerfile
    {
        if (array_key_exists($imageCode, $this->dockerfileServices)) {
            return $this->dockerfileServices[$imageCode];
        }
        return null;
    }

    #[Pure]
    public function hasDockerfileService(string $imageCode): bool
    {
        return array_key_exists($imageCode, $this->dockerfileServices);
    }
}
