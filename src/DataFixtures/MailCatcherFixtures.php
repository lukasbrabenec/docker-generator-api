<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class MailCatcherFixtures extends BaseFixtures
{
    const VERSIONS = [
        'latest'
    ];

    const PORTS = [
        1025 => 1025,
        1080 => 1080,
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'MailCatcher', 'schickling/mailcatcher');

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->_createImageVersion($manager, $image, $version);

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->_createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }
        }
        $manager->flush();
    }
}
