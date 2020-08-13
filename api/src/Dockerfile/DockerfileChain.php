<?php

namespace App\Dockerfile;

use App\Service\Dockerfile\AbstractDockerfile;

class DockerfileChain
{
    /**
     * @var array
     */
    private $dockerfileServices;

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
    public function getDockerfileService($imageName) :? AbstractDockerfile
    {
        if (array_key_exists($imageName, $this->dockerfileServices)) {
            return $this->dockerfileServices[$imageName];
        }
    }

    /**
     * @param string $imageName
     * @return bool
     */
    public function hasDockerfileService($imageName) : bool
    {
        return array_key_exists($imageName, $this->dockerfileServices);
    }
}