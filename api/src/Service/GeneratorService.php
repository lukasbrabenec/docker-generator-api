<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Exception\DockerfileException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GeneratorService
{
    /** @var DockerComposeGenerator */
    private DockerComposeGenerator $dockerComposeGenerator;

    /** @var DockerfileGenerator */
    private DockerfileGenerator $dockerfileGenerator;

    /** @var ZipGenerator */
    private ZipGenerator $zipGenerator;

    /**
     * @param DockerComposeGenerator $dockerComposeGenerator
     * @param DockerfileGenerator $dockerfileGenerator
     * @param ZipGenerator $zipGenerator
     */
    public function __construct(DockerComposeGenerator $dockerComposeGenerator, DockerfileGenerator $dockerfileGenerator, ZipGenerator $zipGenerator)
    {
        $this->dockerComposeGenerator = $dockerComposeGenerator;
        $this->dockerfileGenerator = $dockerfileGenerator;
        $this->zipGenerator = $zipGenerator;
    }

    /**
     * @param GenerateDTO $requestObject
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws DockerfileException
     */
    public function generate(GenerateDTO $requestObject)
    {
        $this->getDockerComposeGenerator()->generate($requestObject);
        $this->getDockerfileGenerator()->generate($requestObject);
        return $this->getZipGenerator()->generateArchive($requestObject);
    }

    /**
     * @return DockerComposeGenerator
     */
    public function getDockerComposeGenerator(): DockerComposeGenerator
    {
        return $this->dockerComposeGenerator;
    }

    /**
     * @param DockerComposeGenerator $dockerComposeGenerator
     */
    public function setDockerComposeGenerator(DockerComposeGenerator $dockerComposeGenerator): void
    {
        $this->dockerComposeGenerator = $dockerComposeGenerator;
    }

    /**
     * @return DockerfileGenerator
     */
    public function getDockerfileGenerator(): DockerfileGenerator
    {
        return $this->dockerfileGenerator;
    }

    /**
     * @param DockerfileGenerator $dockerfileGenerator
     */
    public function setDockerfileGenerator(DockerfileGenerator $dockerfileGenerator): void
    {
        $this->dockerfileGenerator = $dockerfileGenerator;
    }

    /**
     * @return ZipGenerator
     */
    public function getZipGenerator(): ZipGenerator
    {
        return $this->zipGenerator;
    }

    /**
     * @param ZipGenerator $zipGenerator
     */
    public function setZipGenerator(ZipGenerator $zipGenerator): void
    {
        $this->zipGenerator = $zipGenerator;
    }
}