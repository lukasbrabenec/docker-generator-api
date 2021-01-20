<?php

namespace App\Service;

use App\Dockerfile\DockerfileServiceChain;
use App\Entity\DTO\ImageVersionDTO;
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

    private DockerfileServiceChain $dockerfileServiceChain;

    public function __construct(DockerfileServiceChain $dockerfileServiceChain)
    {
        $this->dockerfileServiceChain = $dockerfileServiceChain;
    }

    /**
     * @throws DockerfileException
     */
    public function generate(ImageVersionDTO $requestImageVersion): string
    {
        $imageVersionCode = $requestImageVersion->getImageCode();
        if (!$this->dockerfileServiceChain->hasDockerfileService($imageVersionCode)) {
            throw new DockerfileException(sprintf(self::MISSING_DOCKERFILE_SERVICE_FOR_IMAGE, $imageVersionCode));
        }
        try {
            return $this->dockerfileServiceChain
                ->getDockerfileService($imageVersionCode)
                ->generateDockerfile($requestImageVersion);
        } catch (LoaderError $e) {
            throw new DockerfileException(sprintf(self::MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE, $imageVersionCode, $e));
        } catch (RuntimeError $e) {
            throw new DockerfileException(sprintf(self::RUNTIME_ERROR, $imageVersionCode, $e));
        } catch (SyntaxError $e) {
            throw new DockerfileException(sprintf(self::SYNTAX_ERROR, $imageVersionCode, $e));
        }
    }
}
