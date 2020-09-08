<?php


namespace App\Service\Dockerfile;


use App\Entity\DTO\RequestImageVersion;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DockerfileMailCatcher extends AbstractDockerfile
{
    /**
     * @param RequestImageVersion $requestImageVersion
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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