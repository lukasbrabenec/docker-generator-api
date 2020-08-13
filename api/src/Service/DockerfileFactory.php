<?php

namespace App\Service;

use App\Dockerfile\DockerfileChain;
use App\Entity\DTO\RequestImageVersion;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DockerfileFactory
{
    const MISSING_DOCKERFILE_SERVICE_FOR_IMAGE = 'Missing sender for type: %s';
    const MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE = 'Missing twig template for type: %s';

    /**
     * @var DockerfileChain
     */
    private $dockerfileChain;

    /**
     * @param DockerfileChain $dockerfileChain
     */
    public function __construct(DockerfileChain $dockerfileChain)
    {
        $this->dockerfileChain = $dockerfileChain;
    }

    /**
     * @param RequestImageVersion $requestImageVersion
     * @return string
     * @throws \Exception
     */
    public function generate(RequestImageVersion $requestImageVersion): string
    {
        if (!$this->dockerfileChain->hasDockerfileService($requestImageVersion->getImageName())) {
            throw new \Exception(sprintf(self::MISSING_DOCKERFILE_SERVICE_FOR_IMAGE, $requestImageVersion->getImageName()));
        }
        try {
            return $this->dockerfileChain->getDockerfileService($requestImageVersion->getImageName())->generateDockerfile($requestImageVersion);
        } catch (LoaderError $e) {
            throw new \Exception(sprintf(self::MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE, $requestImageVersion->getImageName()));
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }
}