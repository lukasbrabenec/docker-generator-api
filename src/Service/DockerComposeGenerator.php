<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DockerComposeGenerator
{
    /** @var Environment */
    private Environment $twig;

    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param GenerateDTO $requestObject
     * @return GenerateDTO
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generate(GenerateDTO $requestObject): GenerateDTO
    {
        $dockerComposeText =  $this->twig->render('docker-compose.yml.twig', $requestObject->toArray());
        $requestObject->setDockerComposeText($dockerComposeText);

        return $requestObject;
    }
}