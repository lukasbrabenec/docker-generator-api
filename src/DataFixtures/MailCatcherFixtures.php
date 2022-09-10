<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class MailCatcherFixtures extends BaseFixtures
{
    private const VERSIONS = [
        'latest',
    ];

    private const PORTS = [
        1025 => 1025,
        1080 => 1080,
    ];

    public function load(ObjectManager $manager): void
    {
        $image = $this->getOrCreateImage($manager, 'MailCatcher', 'schickling/mailcatcher', 'Utilities');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->createImageVersion($manager, $image, $version);

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }
        }

        $manager->flush();
    }
}
