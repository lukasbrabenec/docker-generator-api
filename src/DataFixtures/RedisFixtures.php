<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class RedisFixtures extends BaseFixtures
{
    const VERSIONS = [
        'latest',
        'alpine',
        '6',
        '6-alpine',
        '5',
        '5-alpine',
    ];

    const PORTS = [
        6379 => 6379
    ];

    const VOLUMES = [
        './redis/data' => '/data'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'Redis', 'redis');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->_createImageVersion($manager, $image, $version);

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
