<?php

namespace App\DependencyInjection;

use App\Dockerfile\DockerfileChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DockerfileCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(DockerfileChain::class)) {
            return;
        }

        $definition = $container->findDefinition(DockerfileChain::class);

        $taggedServices = $container->findTaggedServiceIds('app.dockerfile');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addDockerfileService', [
                    new Reference($id),
                    $attributes['imageName']
                ]);
            }
        }
    }
}