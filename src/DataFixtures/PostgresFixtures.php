<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class PostgresFixtures extends BaseFixtures
{
    const VERSIONS = [
        'latest',
        '13',
        'alpine',
        '13-alpine',
        '12',
        '12-alpine',
        '11',
        '11-alpine',
        '10',
        '10-alpine',
        '9',
        '9-alpine'
    ];

    const ENVIRONMENT_MAP = [
        'POSTGRES_PASSWORD' => [
            'default' => 'test',
            'required' => true,
            'hidden' => false
        ],
        'POSTGRES_USER' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
        'POSTGRES_DB' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
        'POSTGRES_INITDB_ARGS' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
        'POSTGRES_INITDB_WALDIR' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
        'POSTGRES_HOST_AUTH_METHOD' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ]
    ];

    const PORTS = [
        5432 => 5432
    ];

    const VOLUMES = [
        './postgresql/data' => '/var/lib/postgresql/data'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'PostgreSQL', 'postgres', './php/');

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
