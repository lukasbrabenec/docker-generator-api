<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class WordpressFixtures extends BaseFixtures
{
    private const VERSIONS = [
        'latest',
        '5.6-php7.4-apache',
        '5.6-php7.4-fpm',
        '5.6-php7.4-fpm-alpine',
    ];

    private const ENVIRONMENT_MAP = [
        'WORDPRESS_DB_HOST' => [
            'default' => null,
            'required' => true,
            'hidden' => false,
        ],
        'WORDPRESS_DB_USER' => [
            'default' => null,
            'required' => true,
            'hidden' => false,
        ],
        'WORDPRESS_DB_PASSWORD' => [
            'default' => null,
            'required' => true,
            'hidden' => false,
        ],
        'WORDPRESS_DB_NAME' => [
            'default' => null,
            'required' => true,
            'hidden' => false,
        ],
        'WORDPRESS_TABLE_PREFIX' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
        'WORDPRESS_DEBUG' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
        'WORDPRESS_CONFIG_EXTRA' => [
            'default' => null,
            'required' => false,
            'hidden' => false,
        ],
    ];

    private const PORTS = [
        8080 => 80,
    ];

    private const VOLUMES = [
        './worpdress' => '/var/www/html',
    ];

    public function load(ObjectManager $manager): void
    {
        $image = $this->getOrCreateImage($manager, 'WordPress', 'wordpress', 'Utilities');

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
