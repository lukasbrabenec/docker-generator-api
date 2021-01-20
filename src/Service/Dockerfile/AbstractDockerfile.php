<?php

namespace App\Service\Dockerfile;

use App\Entity\DTO\ImageVersionDTO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractDockerfile
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    abstract public function generateDockerfile(ImageVersionDTO $requestImageVersion): string;

    abstract public function getTemplate(): string;

    public function getTwig(): Environment
    {
        return $this->twig;
    }
}
