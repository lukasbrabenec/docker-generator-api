<?php

namespace App\DataFixtures;

use App\Entity\DockerInstall;
use App\Entity\Group;
use App\Entity\Image;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PhpFixtures extends Fixture
{
    const PHP_VERSIONS = [
        '5.6-fpm',
        '7-fpm',
        '7.1-fpm',
        '7.2-fpm',
        '7.3-fpm',
        '7.4-fpm',
    ];

    const PHP_EXTENSIONS = [
        'MySQL',
        'PostgreSQL',
        'SQLite',
    ];

    public function load(ObjectManager $manager)
    {
        $group = new Group();
        $group->setName('PHP');
        $manager->persist($group);

        $image = new Image();
        $image->setGroup($group);
        $image->setName('php');
        $manager->persist($image);

        foreach (self::PHP_VERSIONS as $version) {
            $imageVersion = new ImageVersion();
            $imageVersion->setVersion($version);
            $imageVersion->setImage($image);
            $manager->persist($imageVersion);
        }

        $imagePort = new ImagePort();
        $imagePort->setImage($image);
        $imagePort->setInward(80);
        $imagePort->setOutward(80);
        $manager->persist($imagePort);

        foreach (self::PHP_EXTENSIONS as $extensionName) {
            $extension = new DockerInstall();
            $extension->setImage($image);
            $extension->setName($extensionName);
            $manager->persist($extension);
        }
        $manager->flush();
    }
}
