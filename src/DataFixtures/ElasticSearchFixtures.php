<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ElasticSearchFixtures extends BaseFixtures implements DependentFixtureInterface
{
    private const VERSIONS = [
        '7.9.0',
    ];

    private const ENVIRONMENT_MAP = [
        'discovery.type' => [
            'default' => 'single-node',
            'required' => true,
            'hidden' => true,
        ],
    ];

    private const PORTS = [
        9200 => 9200,
        9300 => 9300,
    ];

    public function load(ObjectManager $manager): void
    {
        $image = $this->getOrCreateImage($manager, 'ElasticSearch', 'elasticsearch', 'Utilities');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->createImageVersion($manager, $image, $version);

            foreach (self::ENVIRONMENT_MAP as $environmentCode => $options) {
                $this->createImageEnvironment(
                    $manager,
                    $imageVersion,
                    $environmentCode,
                    $options['default'],
                    $options['hidden'],
                    $options['required']
                );
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            PhpFixtures::class,
        ];
    }
}
