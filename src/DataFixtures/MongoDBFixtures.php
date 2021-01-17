<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class MongoDBFixtures extends BaseFixtures
{
    const VERSIONS = [
        'latest',
        '4',
        '4.4',
        '4.2',
        '4.0',
        '3',
        '3.6',
    ];

    const ENVIRONMENT_MAP = [
        'MONGO_INITDB_ROOT_USERNAME' => [
            'default' => null,
            'required' => true,
            'hidden' => false
        ],
        'MONGO_INITDB_ROOT_PASSWORD' => [
            'default' => null,
            'required' => true,
            'hidden' => false
        ],
        'MONGO_INITDB_DATABASE' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
    ];

    const PORTS = [
        27017 => 27017
    ];

    const VOLUMES = [
        './mongo/data' => '/data/db'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'MongoDB', 'mongo');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->_createImageVersion($manager, $image, $version);

            foreach (self::ENVIRONMENT_MAP as $environmentCode => $options) {
                $this->_createImageEnvironment($manager, $imageVersion, $environmentCode, $options['default'], $options['hidden'], $options['required']);
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->_createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $this->_createImageVolume($manager, $imageVersion, $hostPath, $containerPath);
            }
        }
        $manager->flush();
    }
}
