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

    const SPECIAL_EXTENSIONS_OPTIONS_MAP = [
        'npm' => [
            'customCommand' => null,
            'config' => null
        ],
        'tsc-watch' => [
            'customCommand' => null,
            'config' => null
        ],
        'ntypescript' => [
            'customCommand' => null,
            'config' => null
        ],
        'typescript' => [
            'customCommand' => null,
            'config' => null
        ],
        'gulp-cli' => [
            'customCommand' => null,
            'config' => null
        ],
        'yarn' => [
            'customCommand' => null,
            'config' => null
        ],
        'npx' => [
            'customCommand' => null,
            'config' => null
        ],
        'np' => [
            'customCommand' => null,
            'config' => null
        ],
        'npm-name-cli' => [
            'customCommand' => null,
            'config' => null
        ],
        'ndb' => [
            'customCommand' => null,
            'config' => null
        ],
        'node-inspector' => [
            'customCommand' => null,
            'config' => null
        ],
        'create-react-app' => [
            'customCommand' => null,
            'config' => null
        ],
        'create-react-library' => [
            'customCommand' => null,
            'config' => null
        ],
        'create-react-native-cli' => [
            'customCommand' => null,
            'config' => null
        ],
        'eslint' => [
            'customCommand' => null,
            'config' => null
        ],
        'babel-eslint' => [
            'customCommand' => null,
            'config' => null
        ],
        'eslint-config-standard' => [
            'customCommand' => null,
            'config' => null
        ],
        'eslint-config-react' => [
            'customCommand' => null,
            'config' => null
        ],
        'eslint-config-jsx' => [
            'customCommand' => null,
            'config' => null
        ],
        'eslint-plugin-react' => [
            'customCommand' => null,
            'config' => null
        ],
        'eslint-config-prettier' => [
            'customCommand' => null,
            'config' => null
        ],
        'eslint-plugin-prettier' => [
            'customCommand' => null,
            'config' => null
        ],
        'prettier' => [
            'customCommand' => null,
            'config' => null
        ],
        'standard' => [
            'customCommand' => null,
            'config' => null
        ],
        'gulp' => [
            'customCommand' => null,
            'config' => null
        ],
    ];

    const PORTS = [
        8080 => 8080
    ];

    const GENERAL_EXTENSIONS_OPTIONS_MAP = [
        'git' => [
            'customCommand' => null,
            'config' => null
        ]
    ];

    const VOLUMES = [
        './node' => '/home/node/app'
    ];

    /**
     * @param ObjectManager $manager
     * @throws Exception\FixturesException
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'NodeJS', 'node', './node/');

        foreach (self::SPECIAL_EXTENSIONS_OPTIONS_MAP as $extensionName => $extensionOptions) {
            $this->_createExtension($manager, $extensionName, true, $extensionOptions['customCommand']);
        }
        foreach (self::GENERAL_EXTENSIONS_OPTIONS_MAP as $extensionName => $extensionOptions) {
            $this->_createExtension($manager, $extensionName, false, $extensionOptions['customCommand']);
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

            foreach (array_merge(self::SPECIAL_EXTENSIONS_OPTIONS_MAP, self::GENERAL_EXTENSIONS_OPTIONS_MAP) as $extensionName => $extensionOptions) {
                $extension = $this->_getExtension($manager, $extensionName);
                $this->_createImageVersionExtension($manager, $imageVersion, $extension, $extensionOptions['config']);
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
