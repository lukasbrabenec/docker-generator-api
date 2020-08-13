<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Image;
use App\Entity\ImageEnvironment;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
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
            'required' => true
        ],
        'MYSQL_DATABASE' => [
            'default' => 'docker',
            'required' => false
        ],
        'MYSQL_USER' => [
            'default' => null,
            'required' => false
        ],
        'MYSQL_PASSWORD' => [
            'default' => null,
            'required' => false
        ],
        'MYSQL_ALLOW_EMPTY_PASSWORD' => [
            'default' => null,
            'required' => false
        ],
        'MYSQL_ONETIME_PASSWORD' => [
            'default' => null,
            'required' => false
        ]
    ];

    public function load(ObjectManager $manager)
    {
        $group = $manager->getRepository(Group::class)->findOneBy(['name' => 'PHP']);
        if (!is_object($group)) {
            $group = new Group();
            $group->setName('PHP');
            $manager->persist($group);
        }

        $image = new Image();
        $image->setGroup($group);
        $image->setName('mysql');
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
                $manager->persist($environment);
            }

            $imagePort = new ImagePort();
            $imagePort->setImageVersion($imageVersion);
            $imagePort->setInward(3306);
            $imagePort->setOutward(3306);
            $manager->persist($imagePort);
        }
        $manager->flush();
    }
}
