<?php

namespace App\Service;

use App\Dockerfile\DockerfileServiceChain;
use App\Entity\DTO\RequestImageVersion;
use Exception;
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
     * @param RequestImageVersion $requestImageVersion
     * @return string
     * @throws Exception
     */
    public function generate(RequestImageVersion $requestImageVersion): string
    {
        if (!$this->dockerfileServiceChain->hasDockerfileService($requestImageVersion->getImageName())) {
            throw new Exception(sprintf(self::MISSING_DOCKERFILE_SERVICE_FOR_IMAGE, $requestImageVersion->getImageName()));
        }
        try {
            return $this->dockerfileServiceChain->getDockerfileService($requestImageVersion->getImageName())->generateDockerfile($requestImageVersion);
        } catch (LoaderError $e) {
            throw new Exception(sprintf(self::MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE, $requestImageVersion->getImageName()));
        } catch (RuntimeError $e) {
            throw new Exception(sprintf(self::RUNTIME_ERROR, $requestImageVersion->getImageName()));
        } catch (SyntaxError $e) {
            throw new Exception(sprintf(self::SYNTAX_ERROR, $requestImageVersion->getImageName()));
        }
    }
}