<?php

namespace App\Service;

use App\Entity\DTO\GenerateDTO;
use App\Entity\DTO\ImageVersionDTO;
use App\Exception\DockerfileException;
use JetBrains\PhpStorm\Pure;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class DockerfileGenerator
{
    const MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE = 'Missing twig template for type: %s';
    const RUNTIME_ERROR = 'Runtime error for type: %s';
    const SYNTAX_ERROR = 'Syntax error for type: %s';

    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @throws DockerfileException
     */
    public function generate(GenerateDTO $request): void
    {
        /** @var ImageVersionDTO $imageVersionDTO */
        foreach ($request->getImageVersions() as $imageVersionDTO) {
            // image doesn't have dockerfile if location isn't set
            if ($imageVersionDTO->getDockerfileLocation()) {
                $dockerfileText = $this->renderDockerfile($imageVersionDTO);
                $imageVersionDTO->setDockerfileText($dockerfileText);
            }
        }
    }

    /**
     * @throws DockerfileException
     */
    private function renderDockerfile(ImageVersionDTO $imageVersionDTO): string
    {
        $imageVersionCode = $imageVersionDTO->getImageCode();
        try {
            $dockerfileText = $this->getTwig()->render(
                $this->getTemplate($imageVersionCode),
                $imageVersionDTO->toArray()
            );
        } catch (LoaderError $e) {
            throw new DockerfileException(sprintf(self::MISSING_DOCKERFILE_TEMPLATE_FOR_IMAGE, $imageVersionCode, $e));
        } catch (RuntimeError $e) {
            throw new DockerfileException(sprintf(self::RUNTIME_ERROR, $imageVersionCode, $e));
        } catch (SyntaxError $e) {
            throw new DockerfileException(sprintf(self::SYNTAX_ERROR, $imageVersionCode, $e));
        }
        return $dockerfileText;
    }

    #[Pure]
    public function getTemplate(string $imageCode): string
    {
        return sprintf('Dockerfile/%s.twig', $imageCode);
    }

    public function getTwig(): Environment
    {
        return $this->twig;
    }
}
