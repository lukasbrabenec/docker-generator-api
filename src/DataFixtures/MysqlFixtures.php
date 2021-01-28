<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class MysqlFixtures extends BaseFixtures
{
    const VERSIONS = [
        '5.6',
        '5.7',
        '8.0',
    ];

    const ENVIRONMENT_MAP = [
        'MYSQL_ROOT_PASSWORD' => [
            'default' => 'test',
            'required' => true,
            'hidden' => false,
        ],
        'MYSQL_DATABASE' => [
            'default' => 'docker',
            'required' => false,
            'hidden' => false,
        ],
        'MYSQL_USER' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
        'MYSQL_PASSWORD' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
        'MYSQL_ALLOW_EMPTY_PASSWORD' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
        'MYSQL_ONETIME_PASSWORD' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
    ];

    const PORTS = [
        3306 => 3306,
    ];

    const VOLUMES = [
        './mysql/data' => '/var/lib/mysql',
    ];

    public function load(ObjectManager $manager)
    {
        $image = $this->getOrCreateImage($manager, 'MySQL', 'mysql', 'Database');

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
