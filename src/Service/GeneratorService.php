<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Exception\DockerfileException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GeneratorService
{
    private DockerComposeGenerator $dockerComposeGenerator;

    private DockerfileGenerator $dockerfileGenerator;

    private ZipGenerator $zipGenerator;

    public function __construct(
        DockerComposeGenerator $dockerComposeGenerator,
        DockerfileGenerator $dockerfileGenerator,
        ZipGenerator $zipGenerator
    ) {
        $this->dockerComposeGenerator = $dockerComposeGenerator;
        $this->dockerfileGenerator = $dockerfileGenerator;
        $this->zipGenerator = $zipGenerator;
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
