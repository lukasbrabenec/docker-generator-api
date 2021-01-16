<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NodeFixtures extends BaseFixtures implements DependentFixtureInterface
{
    const VERSIONS = [
        'latest',
        '15',
        '15-alpine',
        '15-slim',
        '14',
        '14-alpine',
        '14-slim',
        '12',
        '12-alpine',
        '12-slim',
        '10',
        '10-alpine',
        '10-slim',
    ];

    const ENVIRONMENT_MAP = [
        'NODE_ENV' => [
            'default' => 'production',
            'required' => true,
            'hidden' => false
        ],
    ];

    const SPECIAL_EXTENSIONS_CONFIG_MAP = [
        'npm' => null,
        'tsc-watch' => null,
        'ntypescript' => null,
        'typescript' => null,
        'gulp-cli' => null,
        'yarn' => null,
        'npx' => null,
        'np' => null,
        'npm-name-cli' => null,
        'ndb' => null,
        'node-inspector' => null,
        'create-react-app' => null,
        'create-react-library' => null,
        'create-react-native-cli' => null,
        'eslint' => null,
        'babel-eslint' => null,
        'eslint-config-standard' => null,
        'eslint-config-react' => null,
        'eslint-config-jsx' => null,
        'eslint-plugin-react' => null,
        'eslint-config-prettier' => null,
        'eslint-plugin-prettier' => null,
        'prettier' => null,
        'standard' => null,
        'gulp' => null,
    ];

    const PORTS = [
        8080 => 8080
    ];

    const GENERAL_EXTENSIONS_CONFIG_MAP = [
        'git' => null
    ];

    const VOLUMES = [
        './node' => '/home/node/app'
    ];

    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'NodeJS', 'node', './node/');

        foreach (self::SPECIAL_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
            $this->_createExtension($manager, $extensionName, true);
        }
        foreach (self::GENERAL_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
            $this->_createExtension($manager, $extensionName, false);
        }
        $manager->flush();

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->_createImageVersion($manager, $image, $version);

            foreach (self::ENVIRONMENT_MAP as $environmentCode => $options) {
                $this->_createImageEnvironment($manager, $imageVersion, $environmentCode, $options['default'], $options['hidden'], $options['required']);
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->_createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $this->_createImageVolume($manager, $imageVersion, $hostPath, $containerPath);
            }

            foreach (array_merge(self::SPECIAL_EXTENSIONS_CONFIG_MAP, self::GENERAL_EXTENSIONS_CONFIG_MAP) as $extensionName => $extensionConfig) {
                $extension = $this->_getExtension($manager, $extensionName);
                $this->_createImageVersionExtension($manager, $imageVersion, $extension, $extensionConfig);
            }
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            PhpFixtures::class
        ];
    }
}
