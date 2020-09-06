<?php


namespace App\Service\Dockerfile;


use App\Entity\DTO\RequestImageVersion;

class DockerfileMailCatcher extends AbstractDockerfile
{
    /**
     * @param RequestImageVersion $requestImageVersion
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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
        return 'Dockerfile/mailcatcher.twig';
    }
}