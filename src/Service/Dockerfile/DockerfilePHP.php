<?php

namespace App\Service\Dockerfile;

use App\Entity\DTO\ImageVersionDTO;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DockerfilePHP extends AbstractDockerfile
{
    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generateDockerfile(ImageVersionDTO $requestImageVersion): string
    {
        return $this->getTwig()->render($this->getTemplate(), $requestImageVersion->toArray());
    }

    public function getTemplate(): string
    {
        return 'Dockerfile/php.twig';
    }
}
