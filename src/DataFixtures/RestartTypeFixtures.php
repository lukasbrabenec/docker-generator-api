<?php

namespace App\DataFixtures;

use App\Entity\RestartType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RestartTypeFixtures extends Fixture
{
    private const TYPES = [
        'no',
        'always',
        'on-failure',
        'unless-stopped',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TYPES as $type) {
            $restartType = new RestartType();
            $restartType->setType($type);
            $manager->persist($restartType);
        }

        $manager->flush();
    }
}
