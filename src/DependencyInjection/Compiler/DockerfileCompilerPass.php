<?php

namespace App\DependencyInjection\Compiler;

use App\Dockerfile\DockerfileServiceChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DockerfileCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(DockerfileServiceChain::class)) {
            return;
        }

        $definition = $container->findDefinition(DockerfileServiceChain::class);

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