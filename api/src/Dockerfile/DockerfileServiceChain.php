<?php

namespace App\Dockerfile;

use App\Service\Dockerfile\AbstractDockerfile;

class DockerfileServiceChain
{
    /**
     * @var array
     */
    private array $dockerfileServices;

    public function __construct()
    {
        $this->dockerfileServices = [];
    }

    public function addDockerfileService(AbstractDockerfile $dockerfile, $imageName) : void
    {
        $this->dockerfileServices[$imageName] = $dockerfile;
    }

    /**
     * @param string $imageName
     * @return AbstractDockerfile|void
     */
    public function getDockerfileService(string $imageName) :? AbstractDockerfile
    {
        if (array_key_exists($imageName, $this->dockerfileServices)) {
            return $this->dockerfileServices[$imageName];
        }
    }

    /**
     * @param string $imageName
     * @return bool
     */
    public function hasDockerfileService(string $imageName) : bool
    {
        return array_key_exists($imageName, $this->dockerfileServices);
    }

    /**
     * @return AbstractDockerfile
     */
    public function getDefaultDockerfileService() : AbstractDockerfile
    {
        return $this->dockerfileServices['default'];
    }
}