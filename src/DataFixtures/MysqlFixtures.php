<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\ImageEnvironment;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use App\Entity\ImageVolume;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MysqlFixtures extends Fixture
{
    const VERSIONS = [
        '5.7',
        '8.0'
    ];

    const ENVIRONMENT_MAP = [
        'MYSQL_ROOT_PASSWORD' => [
            'default' => 'test',
            'required' => true,
            'hidden' => false
        ],
        'MYSQL_DATABASE' => [
            'default' => 'docker',
            'required' => false,
            'hidden' => false
        ],
        'MYSQL_USER' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
        'MYSQL_PASSWORD' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
        'MYSQL_ALLOW_EMPTY_PASSWORD' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ],
        'MYSQL_ONETIME_PASSWORD' => [
            'default' => null,
            'required' => false,
            'hidden' => false
        ]
    ];

    const PORTS = [
        3306 => 3306
    ];

    const VOLUMES = [
        './mysql/data' => '/var/lib/mysql'
    ];

    public function load(ObjectManager $manager)
    {
        $image = new Image();
        $image->setName('MySQL');
        $image->setCode('mysql');
        $image->setDockerfileLocation('./mysql/build/');
        $manager->persist($image);

        foreach (self::VERSIONS as $version) {
            $imageVersion = new ImageVersion();
            $imageVersion->setVersion($version);
            $imageVersion->setImage($image);
            $manager->persist($imageVersion);

            foreach (self::ENVIRONMENT_MAP as $environmentCode => $options) {
                $environment = new ImageEnvironment();
                $environment->setImageVersion($imageVersion);
                $environment->setCode($environmentCode);
                $environment->setDefaultValue($options['default']);
                $environment->setRequired($options['required']);
                $environment->setHidden($options['hidden']);
                $manager->persist($environment);
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $imagePort = new ImagePort();
                $imagePort->setImageVersion($imageVersion);
                $imagePort->setInward($inwardPort);
                $imagePort->setOutward($outwardPort);
                $manager->persist($imagePort);
            }

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $imageVolume = new ImageVolume();
                $imageVolume->setImageVersion($imageVersion);
                $imageVolume->setHostPath($hostPath);
                $imageVolume->setContainerPath($containerPath);
                $manager->persist($imageVolume);
            }
        }
        $manager->flush();
    }
}
