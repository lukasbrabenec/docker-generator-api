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
        6379 => 6379,
    ];

    const VOLUMES = [
        './redis/data' => '/data',
    ];

    public function load(ObjectManager $manager)
    {
        $image = $this->getOrCreateImage($manager, 'Redis', 'redis', 'Utilities');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->createImageVersion($manager, $image, $version);

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
