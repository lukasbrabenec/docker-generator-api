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

class ElasticSearchFixtures extends Fixture implements DependentFixtureInterface
{
    const VERSIONS = [
        '7.9.0'
    ];

    const ENVIRONMENT_MAP = [
        'discovery.type' => [
            'default' => 'single-node',
            'required' => true,
            'hidden' => true
        ]
    ];

    const PORTS = [
        9200 => 9200,
        9300 => 9300
    ];

    public function load(ObjectManager $manager)
    {
        /** @var Group $group */
        $group = $manager->getRepository(Group::class)->findOneBy(['name' => 'PHP']);

        $image = new Image();
        $image->setGroup($group);
        $image->setName('elasticsearch');
        $image->setCode('elasticsearch');
        $image->setDockerfileLocation(null);
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
