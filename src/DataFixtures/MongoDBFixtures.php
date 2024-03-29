<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class MongoDBFixtures extends BaseFixtures
{
    private const VERSIONS = [
        'latest',
        '4',
        '4.4',
        '4.2',
        '4.0',
        '3',
        '3.6',
    ];

    private const ENVIRONMENT_MAP = [
        'MONGO_INITDB_ROOT_USERNAME' => [
            'default' => null,
            'required' => true,
            'hidden' => false,
        ],
        'MONGO_INITDB_ROOT_PASSWORD' => [
            'default' => null,
            'required' => true,
            'hidden' => false,
        ],
        'MONGO_INITDB_DATABASE' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
    ];

    private const PORTS = [
        27017 => 27017,
    ];

    private const VOLUMES = [
        './mongo/data' => '/data/db',
    ];

    public function load(ObjectManager $manager): void
    {
        $image = $this->getOrCreateImage($manager, 'MongoDB', 'mongo', 'Database');

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

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $this->createImageVolume($manager, $imageVersion, $hostPath, $containerPath);
            }
        }

        $manager->flush();
    }
}
