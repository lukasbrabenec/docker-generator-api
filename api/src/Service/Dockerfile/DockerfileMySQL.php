<?php

namespace App\Service\Dockerfile;

use App\Entity\DTO\RequestImageVersion;

class DockerfileMySQL extends AbstractDockerfile
{
    /**
     * @param RequestImageVersion $requestImageVersion
     * @return string
     */
    public function generateDockerfile(RequestImageVersion $requestImageVersion): string
    {
        return $this->getTwig()->render($this->getTemplate(), $requestImageVersion->toArray());
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return 'Dockerfile/mysql.twig';
    }
}