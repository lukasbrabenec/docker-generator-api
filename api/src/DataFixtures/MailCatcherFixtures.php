<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Image;
use App\Entity\ImageEnvironment;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MailCatcherFixtures extends Fixture implements DependentFixtureInterface
{
    const VERSIONS = [
        '0.7.1'
    ];

    const PORTS = [
        1025 => 1025,
        1080 => 1080,
    ];

    public function load(ObjectManager $manager)
    {
        /** @var Group $group */
        $group = $manager->getRepository(Group::class)->findOneBy(['name' => 'PHP']);

        $image = new Image();
        $image->setGroup($group);
        $image->setName('MailCatcher');
        $image->setCode('mailcatcher');
        $image->setDockerfileLocation('./mailcatcher/build/');
        $manager->persist($image);

        foreach (self::VERSIONS as $version) {
            $imageVersion = new ImageVersion();
            $imageVersion->setVersion($version);
            $imageVersion->setImage($image);
            $manager->persist($imageVersion);

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $imagePort = new ImagePort();
                $imagePort->setImageVersion($imageVersion);
                $imagePort->setInward($inwardPort);
                $imagePort->setOutward($outwardPort);
                $manager->persist($imagePort);
            }
        }
        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies()
    {
        return [
            PhpFixtures::class
        ];
    }
}
