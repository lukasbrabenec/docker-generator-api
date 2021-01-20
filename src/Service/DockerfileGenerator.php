<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\ImageVersionDTO;
use App\Exception\DockerfileException;
use Twig\Environment;

class DockerfileGenerator
{
    private Environment $twig;

    private DockerfileFactory $dockerfileFactory;

    public function __construct(Environment $twig, DockerfileFactory $dockerfileFactory)
    {
        $this->twig = $twig;
        $this->dockerfileFactory = $dockerfileFactory;
    }

    /**
     * @throws DockerfileException
     */
    public function generate(GenerateDTO $request): void
    {
        /** @var ImageVersionDTO $imageVersion */
        foreach ($request->getImageVersions() as $imageVersion) {
            // image doesn't have dockerfile if location isn't set
            if ($imageVersion->getDockerfileLocation()) {
                $dockerfileText = $this->dockerfileFactory->generate($imageVersion);
                $imageVersion->setDockerfileText($dockerfileText);
            }
        }
    }
}
