<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\GenerateImageVersionDTO;
use Exception;
use Twig\Environment;

class DockerfileGenerator
{
    /** @var Environment */
    private Environment $twig;

    /** @var DockerfileFactory */
    private DockerfileFactory $dockerfileFactory;

    /**
     * @param Environment $twig
     * @param DockerfileFactory $dockerfileFactory
     */
    public function __construct(Environment $twig, DockerfileFactory $dockerfileFactory)
    {
        $this->twig = $twig;
        $this->dockerfileFactory = $dockerfileFactory;
    }

    /**
     * @param GenerateDTO $request
     * @throws Exception
     */
    public function generate(GenerateDTO $request) : void
    {
        /** @var GenerateImageVersionDTO $imageVersion */
        foreach ($request->getImageVersions() as $imageVersion) {
            // image doesn't have dockerfile if location isn't set
            if ($imageVersion->getDockerfileLocation()) {
                $dockerfileText = $this->dockerfileFactory->generate($imageVersion);
                $imageVersion->setDockerfileText($dockerfileText);
            }
        }
    }
}
