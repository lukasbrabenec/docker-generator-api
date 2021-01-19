<?php

namespace App\DataFixtures;

use App\Entity\RestartType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestartTypeFixtures extends Fixture
{
    const TYPES = [
        "no",
        "always",
        "on-failure",
        "unless-stopped"
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::TYPES as $type) {
            $restartType = new RestartType();
            $restartType->setType($type);
            $manager->persist($restartType);
        }
        $manager->flush();
    }
}