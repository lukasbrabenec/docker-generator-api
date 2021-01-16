<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ElasticSearchFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const VERSIONS = [
        '7.9.0'
    ];

    const ENVIRONMENT_MAP = [
        'discovery.type' => [
            'default' => 'single-node',
            'required' => true,
            'hidden' => true
        ]
    ];

    const PORTS = [
        9200 => 9200,
        9300 => 9300
    ];

    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'ElasticSearch', 'elasticsearch');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->_createImageVersion($manager, $image, $version);

            foreach (self::ENVIRONMENT_MAP as $environmentCode => $options) {
                $this->_createImageEnvironment($manager, $imageVersion, $environmentCode, $options['default'], $options['hidden'], $options['required']);
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->_createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
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
            PhpFixtures::class
        ];
    }
}
