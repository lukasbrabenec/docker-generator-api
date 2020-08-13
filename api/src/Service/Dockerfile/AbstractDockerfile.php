<?php

namespace App\Service\Dockerfile;

use App\Entity\DTO\RequestImageVersion;
use Twig\Environment;

abstract class AbstractDockerfile
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param RequestImageVersion $requestImageVersion
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public abstract function generateDockerfile(RequestImageVersion $requestImageVersion): string;

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