<?php

namespace App\DataFixtures;

use App\Entity\ComposeFormatVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ComposeFormatVersionFixtures extends Fixture
{
    const COMPOSE_VERSION_DOCKER_ENGINE_RELEASE_MAP = [
        '2.0' => "1.10.0+",
        '2.1' => "1.12.0+",
        '2.2' => "1.13.0+",
        '2.3' => "17.06.0+",
        '2.4' => "17.12.0+",
        '3.0' => "1.13.0+",
        '3.1' => "1.13.1+",
        '3.2' => "17.04.0+",
        '3.3' => "17.06.0+",
        '3.4' => "17.09.0+",
        '3.5' => "17.12.0+",
        '3.6' => "18.02.0+",
        '3.7' => "18.06.0+",
        '3.8' => "19.03.0+"
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::COMPOSE_VERSION_DOCKER_ENGINE_RELEASE_MAP as $composeVersion => $dockerEngineRelease) {
            $composeFormatVersion = new ComposeFormatVersion();
            $composeFormatVersion->setComposeVersion($composeVersion);
            $composeFormatVersion->setDockerEngineRelease($dockerEngineRelease);
            $manager->persist($composeFormatVersion);
        }
        $manager->flush();
    }
}