<?php

namespace App\Service\Dockerfile;

use App\Entity\DTO\GenerateImageVersionDTO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractDockerfile
{
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param GenerateImageVersionDTO $requestImageVersion
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public abstract function generateDockerfile(GenerateImageVersionDTO $requestImageVersion): string;

    /**
     * @return string
     */
    public abstract function getTemplate(): string;

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }
}