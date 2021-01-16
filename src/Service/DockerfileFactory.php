<?php

namespace App\Service;

use App\Dockerfile\DockerfileServiceChain;
use App\Entity\DTO\GenerateImageVersionDTO;
use App\Exception\DockerfileException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DockerfileFactory
{
    const MISSING_DOCKERFILE_SERVICE_FOR_IMAGE = 'Missing service for type: %s';
    const MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE = 'Missing twig template for type: %s';
    const RUNTIME_ERROR = 'Runtime error for type: %s';
    const SYNTAX_ERROR = 'Syntax error for type: %s';

    /**
     * @var DockerfileServiceChain
     */
    private DockerfileServiceChain $dockerfileServiceChain;

    /**
     * @param DockerfileServiceChain $dockerfileServiceChain
     */
    public function __construct(DockerfileServiceChain $dockerfileServiceChain)
    {
        $this->dockerfileServiceChain = $dockerfileServiceChain;
    }

    /**
     * @param GenerateImageVersionDTO $requestImageVersion
     * @return string
     * @throws DockerfileException
     */
    public function generate(GenerateImageVersionDTO $requestImageVersion): string
    {
        if (!$this->dockerfileServiceChain->hasDockerfileService($requestImageVersion->getImageCode())) {
            throw new DockerfileException(sprintf(self::MISSING_DOCKERFILE_SERVICE_FOR_IMAGE, $requestImageVersion->getImageCode()));
        }
        try {
            return $this->dockerfileServiceChain->getDockerfileService($requestImageVersion->getImageCode())->generateDockerfile($requestImageVersion);
        } catch (LoaderError $e) {
            throw new DockerfileException(sprintf(self::MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE, $requestImageVersion->getImageCode()));
        } catch (RuntimeError $e) {
            throw new DockerfileException(sprintf(self::RUNTIME_ERROR, $requestImageVersion->getImageCode()));
        } catch (SyntaxError $e) {
            throw new DockerfileException(sprintf(self::SYNTAX_ERROR, $requestImageVersion->getImageCode()));
        }
    }
}