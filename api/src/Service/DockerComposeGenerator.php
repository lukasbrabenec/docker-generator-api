<?php

namespace App\Service;

use App\Entity\DTO\Request;
use Twig\Environment;

class DockerComposeGenerator
{
    /** @var Environment */
    private $twig;

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
     */
    public function generate(Request $requestObject): Request
    {
        $dockerComposeText =  $this->twig->render('docker-compose.yml.twig', $requestObject->toArray());
        $requestObject->setDockerComposeText($dockerComposeText);

        return $requestObject;
    }
}