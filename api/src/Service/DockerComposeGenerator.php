<?php

namespace App\Service;

use App\Entity\DTO\Request;
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
     * @param Request $requestObject
     * @return Request
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generate(Request $requestObject): Request
    {
        $dockerComposeText =  $this->twig->render('docker-compose.yml.twig', $requestObject->toArray());
        $requestObject->setDockerComposeText($dockerComposeText);

        return $requestObject;
    }
}