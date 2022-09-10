<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Exception\DockerfileException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GeneratorService
{
    public function __construct(
        private readonly DockerComposeGenerator $dockerComposeGenerator,
        private readonly DockerfileGenerator $dockerfileGenerator,
        private readonly ZipGenerator $zipGenerator
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws DockerfileException
     */
    public function generate(GenerateDTO $requestObject): string
    {
        $this->getDockerComposeGenerator()->generate($requestObject);
        $this->getDockerfileGenerator()->generate($requestObject);

        return $this->getZipGenerator()->generateArchive($requestObject);
    }

    public function getDockerComposeGenerator(): DockerComposeGenerator
    {
        return $this->dockerComposeGenerator;
    }

    public function getDockerfileGenerator(): DockerfileGenerator
    {
        return $this->dockerfileGenerator;
    }

    public function getZipGenerator(): ZipGenerator
    {
        return $this->zipGenerator;
    }
}
