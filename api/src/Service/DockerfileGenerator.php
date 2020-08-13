<?php

namespace App\Service;

use App\Entity\DTO\Request;
use App\Entity\DTO\RequestImageVersion;
use App\Service\DockerfileFactory;
use Twig\Environment;

class DockerfileGenerator
{
    /** @var Environment */
    private $twig;

    /** @var DockerfileFactory */
    private $dockerfileFactory;

    /**
     * DockerfileGenerator constructor.
     * @param Environment $twig
     * @param DockerfileFactory $dockerfileFactory
     */
    public function __construct(Environment $twig, DockerfileFactory $dockerfileFactory)
    {
        $this->twig = $twig;
        $this->dockerfileFactory = $dockerfileFactory;
    }

    /**
     * @param Request $request
     */
    public function generate(Request $request) : void
    {
        /** @var RequestImageVersion $imageVersion */
        foreach ($request->getImageVersions() as $imageVersion) {
            $dockerfileText = $this->dockerfileFactory->generate($imageVersion);
            $imageVersion->setDockerfileText($dockerfileText);
        }
    }
}
