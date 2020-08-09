<?php

namespace App\Service;

use Twig\Environment;

class DockerfileGenerator
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
     * @param string $twigTemplate
     * @param array $data
     * @return string
     */
    public function generate($twigTemplate, $data) : string
    {
        return $this->twig->render($twigTemplate, $data);
    }
}
