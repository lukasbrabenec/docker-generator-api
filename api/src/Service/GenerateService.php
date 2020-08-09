<?php

namespace App\Service;

class GenerateService
{
    /** @var DockerComposeGenerator */
    private $dockerComposeGenerator;

    /** @var DockerfileGenerator */
    private $dockerfileGenerator;

    /** @var ZipGenerator */
    private $zipGenerator;

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


    public function generate()
    {

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