<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class MailCatcherFixtures extends BaseFixtures
{
    const VERSIONS = [
        'latest',
    ];

    const PORTS = [
        1025 => 1025,
        1080 => 1080,
    ];

    public function load(ObjectManager $manager)
    {
        $image = $this->getOrCreateImage($manager, 'MailCatcher', 'schickling/mailcatcher');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->createImageVersion($manager, $image, $version);

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }
        }
        $manager->flush();
    }
}
