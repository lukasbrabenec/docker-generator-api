<?php

namespace App\DataFixtures;

use App\Entity\Extension;
use App\Entity\Image;
use App\Entity\ImageEnvironment;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use App\Entity\ImageVersionExtension;
use App\Entity\ImageVolume;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @param string $name
     * @param string $code
     * @param string|null $dockerfileLocation
     * @return Image
     */
    protected function _getOrCreateImage(ObjectManager $manager, string $name, string $code, string $dockerfileLocation = null): Image
    {
        $image = $manager->getRepository(Image::class)->findOneBy(['name' => $name]);
        if (!is_object($image)) {
            $image = new Image();
            $image->setName($name);
            $image->setCode($code);
            $image->setDockerfileLocation($dockerfileLocation);
            $manager->persist($image);
        }
        return $image;
    }

    /**
     * @param ObjectManager $manager
     * @param Image $image
     * @param string $version
     * @return ImageVersion
     */
    protected function _createImageVersion(ObjectManager $manager, Image $image, string $version): ImageVersion
    {
        $imageVersion = new ImageVersion();
        $imageVersion->setVersion($version);
        $imageVersion->setImage($image);
        $manager->persist($imageVersion);
        return $imageVersion;
    }

    /**
     * @param ObjectManager $manager
     * @param ImageVersion $imageVersion
     * @param string $code
     * @param string|null $defaultValue
     * @param bool $hidden
     * @param bool $required
     * @return ImageEnvironment
     */
    protected function _createImageEnvironment(ObjectManager $manager, ImageVersion $imageVersion, string $code, string $defaultValue = null, bool $hidden = false, bool $required = false): ImageEnvironment
    {
        $imageEnvironment = new ImageEnvironment();
        $imageEnvironment->setCode($code);
        $imageEnvironment->setImageVersion($imageVersion);
        $imageEnvironment->setDefaultValue($defaultValue);
        $imageEnvironment->setHidden($hidden);
        $imageEnvironment->setRequired($required);
        $manager->persist($imageEnvironment);
        return $imageEnvironment;
    }

    /**
     * @param ObjectManager $manager
     * @param ImageVersion $imageVersion
     * @param int $inward
     * @param int $outward
     * @return ImagePort
     */
    protected function _createImagePort(ObjectManager $manager, ImageVersion $imageVersion, int $inward, int $outward): ImagePort
    {
        $imagePort = new ImagePort();
        $imagePort->setImageVersion($imageVersion);
        $imagePort->setInward($inward);
        $imagePort->setOutward($outward);
        $manager->persist($imagePort);
        return $imagePort;
    }

    /**
     * @param ObjectManager $manager
     * @param string $name
     * @param bool $special
     * @return Extension
     */
    protected function _createExtension(ObjectManager $manager, string $name, bool $special): Extension
    {
        $extension = $manager->getRepository(Extension::class)->findOneBy(['name' => $name, 'special' => $special]);
        if (!is_object($extension)) {
            $extension = new Extension();
            $extension->setName($name);
            $extension->setSpecial($special);
            $manager->persist($extension);
        }
        return $extension;
    }

    /**
     * @param ObjectManager $manager
     * @param ImageVersion $imageVersion
     * @param Extension $extension
     * @param string|null $config
     * @return ImageVersionExtension
     */
    protected function _createImageVersionExtension(ObjectManager $manager, ImageVersion $imageVersion, Extension $extension, string $config = null): ImageVersionExtension
    {
        $imageVersionExtension = new ImageVersionExtension();
        $imageVersionExtension->setImageVersion($imageVersion);
        $imageVersionExtension->setExtension($extension);
        $imageVersionExtension->setConfig($config);
        $manager->persist($imageVersionExtension);
        return $imageVersionExtension;
    }

    /**
     * @param ObjectManager $manager
     * @param ImageVersion $imageVersion
     * @param string $hostPath
     * @param string $containerPath
     * @return ImageVolume
     */
    protected function _createImageVolume(ObjectManager $manager, ImageVersion $imageVersion, string $hostPath, string $containerPath): ImageVolume
    {
        $imageVolume = new ImageVolume();
        $imageVolume->setImageVersion($imageVersion);
        $imageVolume->setHostPath($hostPath);
        $imageVolume->setContainerPath($containerPath);
        $manager->persist($imageVolume);
        return $imageVolume;
    }

    /**
     * @param ObjectManager $manager
     * @param string $name
     * @return Extension
     * @throws \Exception
     */
    protected function _getExtension(ObjectManager $manager, string $name): Extension
    {
        /** @var Extension $extension */
        $extension = $manager->getRepository(Extension::class)->findOneBy(['name' => $name]);
        if (!is_object($extension)) {
            throw new \Exception(sprintf("extension %s doesnt exist", $name));
        }
        return $extension;
    }
}