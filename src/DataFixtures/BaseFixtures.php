<?php

namespace App\DataFixtures;

use App\DataFixtures\Exception\FixturesException;
use App\Entity\Environment;
use App\Entity\Extension;
use App\Entity\Image;
use App\Entity\ImageGroup;
use App\Entity\ImageVersion;
use App\Entity\ImageVersionExtension;
use App\Entity\Port;
use App\Entity\Volume;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixtures extends Fixture
{
    protected function getOrCreateImage(
        ObjectManager $manager,
        string $name,
        string $code,
        string $groupName,
        ?string $dockerfileLocation = null
    ): Image {
        $image = $manager->getRepository(Image::class)->findOneBy(['name' => $name]);

        if ($image === null) {
            $image = new Image();
            $image->setName($name);
            $image->setCode($code);
            $image->setDockerfileLocation($dockerfileLocation);
        }

        $group = $manager->getRepository(ImageGroup::class)->findOneBy(['name' => $groupName]);

        if ($group === null) {
            $group = new ImageGroup();
            $group->setName($groupName);
            $manager->persist($group);
        }

        $image->setGroup($group);
        $manager->persist($image);

        return $image;
    }

    protected function createImageVersion(ObjectManager $manager, Image $image, string $version): ImageVersion
    {
        $imageVersion = new ImageVersion();
        $imageVersion->setVersion($version);
        $imageVersion->setImage($image);
        $manager->persist($imageVersion);

        return $imageVersion;
    }

    protected function createImageEnvironment(
        ObjectManager $manager,
        ImageVersion $imageVersion,
        string $code,
        ?string $defaultValue = null,
        bool $hidden = false,
        bool $required = false
    ): Environment {
        $imageEnvironment = new Environment();
        $imageEnvironment->setCode($code);
        $imageEnvironment->setImageVersion($imageVersion);
        $imageEnvironment->setDefaultValue($defaultValue);
        $imageEnvironment->setHidden($hidden);
        $imageEnvironment->setRequired($required);
        $manager->persist($imageEnvironment);

        return $imageEnvironment;
    }

    protected function createImagePort(
        ObjectManager $manager,
        ImageVersion $imageVersion,
        int $inward,
        int $outward
    ): Port {
        $imagePort = new Port();
        $imagePort->setImageVersion($imageVersion);
        $imagePort->setInward($inward);
        $imagePort->setOutward($outward);
        $manager->persist($imagePort);

        return $imagePort;
    }

    protected function createExtension(
        ObjectManager $manager,
        string $name,
        bool $special,
        ?string $customCommand = null
    ): Extension {
        $extension = $manager->getRepository(Extension::class)->findOneBy(['name' => $name, 'special' => $special]);

        if ($extension === null) {
            $extension = new Extension();
            $extension->setName($name);
            $extension->setSpecial($special);
            $extension->setCustomCommand($customCommand);
            $manager->persist($extension);
        }

        return $extension;
    }

    protected function createImageVersionExtension(
        ObjectManager $manager,
        ImageVersion $imageVersion,
        Extension $extension,
        ?string $config = null
    ): ImageVersionExtension {
        $imageVersionExtension = new ImageVersionExtension();
        $imageVersionExtension->setImageVersion($imageVersion);
        $imageVersionExtension->setExtension($extension);
        $imageVersionExtension->setConfig($config);
        $manager->persist($imageVersionExtension);

        return $imageVersionExtension;
    }

    protected function createImageVolume(
        ObjectManager $manager,
        ImageVersion $imageVersion,
        string $hostPath,
        string $containerPath
    ): Volume {
        $imageVolume = new Volume();
        $imageVolume->setImageVersion($imageVersion);
        $imageVolume->setHostPath($hostPath);
        $imageVolume->setContainerPath($containerPath);
        $manager->persist($imageVolume);

        return $imageVolume;
    }

    /**
     * @throws FixturesException
     */
    protected function getExtension(ObjectManager $manager, string $name): Extension
    {
        /** @var Extension $extension */
        $extension = $manager->getRepository(Extension::class)->findOneBy(['name' => $name]);

        if ($extension === null) {
            throw new FixturesException(\sprintf('extension %s doesnt exist', $name));
        }

        return $extension;
    }
}
