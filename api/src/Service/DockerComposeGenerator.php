<?php

namespace App\Service;

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
     * @param array $data
     * @return string
     */
    public function generate($data): string
    {
        return $this->twig->render('docker-compose.yml.twig', $data);
    }
}