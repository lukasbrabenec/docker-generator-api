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

    const PHP_VERSIONS_EXTENSION_EXCLUDE_MAP = [
        'latest' => [
            'bash',
        ],
        '15' => [
            'bash',
        ],
        '14' => [
            'bash',
        ],
        '12' => [
            'bash',
        ],
        '10' => [
            'bash',
        ],
    ];

    const ENVIRONMENT_MAP = [
        'NODE_ENV' => [
            'default' => 'development',
            'required' => true,
            'hidden' => false,
        ],
    ];

    const SPECIAL_EXTENSIONS_OPTIONS_MAP = [
        'tsc-watch' => [
            'customCommand' => null,
            'config' => null,
        ],
        'ntypescript' => [
            'customCommand' => null,
            'config' => null,
        ],
        'typescript' => [
            'customCommand' => null,
            'config' => null,
        ],
        'gulp-cli' => [
            'customCommand' => null,
            'config' => null,
        ],
        'np' => [
            'customCommand' => null,
            'config' => null,
        ],
        'npm-name-cli' => [
            'customCommand' => null,
            'config' => null,
        ],
        'ndb' => [
            'customCommand' => null,
            'config' => null,
        ],
        'node-inspector' => [
            'customCommand' => null,
            'config' => null,
        ],
        'create-react-app' => [
            'customCommand' => null,
            'config' => null,
        ],
        'create-react-library' => [
            'customCommand' => null,
            'config' => null,
        ],
        'create-react-native-cli' => [
            'customCommand' => null,
            'config' => null,
        ],
        'eslint' => [
            'customCommand' => null,
            'config' => null,
        ],
        'babel-eslint' => [
            'customCommand' => null,
            'config' => null,
        ],
        'eslint-config-standard' => [
            'customCommand' => null,
            'config' => null,
        ],
        'eslint-config-react' => [
            'customCommand' => null,
            'config' => null,
        ],
        'eslint-config-jsx' => [
            'customCommand' => null,
            'config' => null,
        ],
        'eslint-plugin-react' => [
            'customCommand' => null,
            'config' => null,
        ],
        'eslint-config-prettier' => [
            'customCommand' => null,
            'config' => null,
        ],
        'eslint-plugin-prettier' => [
            'customCommand' => null,
            'config' => null,
        ],
        'prettier' => [
            'customCommand' => null,
            'config' => null,
        ],
        'standard' => [
            'customCommand' => null,
            'config' => null,
        ],
        'gulp' => [
            'customCommand' => null,
            'config' => null,
        ],
    ];

    const PORTS = [
        8080 => 8080,
    ];

    const GENERAL_EXTENSIONS_OPTIONS_MAP = [
        'bash' => [
            'customCommand' => null,
            'config' => null,
        ],
        'git' => [
            'customCommand' => null,
            'config' => null,
        ],
    ];

    const VOLUMES = [
        './node' => '/home/node/app',
    ];

    /**
     * @throws Exception\FixturesException
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->getOrCreateImage($manager, 'NodeJS', 'node', 'Development Environment', './node/');

        foreach (self::SPECIAL_EXTENSIONS_OPTIONS_MAP as $extensionName => $extensionOptions) {
            $this->createExtension($manager, $extensionName, true, $extensionOptions['customCommand']);
        }
        foreach (self::GENERAL_EXTENSIONS_OPTIONS_MAP as $extensionName => $extensionOptions) {
            $this->createExtension($manager, $extensionName, false, $extensionOptions['customCommand']);
        }
        $manager->flush();

        foreach (self::VERSIONS as $version) {
            $imageVersion = $this->createImageVersion($manager, $image, $version);

            foreach (self::ENVIRONMENT_MAP as $environmentCode => $options) {
                $this->createImageEnvironment(
                    $manager,
                    $imageVersion,
                    $environmentCode,
                    $options['default'],
                    $options['hidden'],
                    $options['required']
                );
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $this->createImageVolume($manager, $imageVersion, $hostPath, $containerPath);
            }

            foreach (array_merge(
                self::SPECIAL_EXTENSIONS_OPTIONS_MAP,
                self::GENERAL_EXTENSIONS_OPTIONS_MAP
            ) as $extensionName => $extensionOptions
            ) {
                if (isset(self::PHP_VERSIONS_EXTENSION_EXCLUDE_MAP[$version])
                    && !in_array($extensionName, self::PHP_VERSIONS_EXTENSION_EXCLUDE_MAP[$version])
                ) {
                    $extension = $this->getExtension($manager, $extensionName);
                    $this->createImageVersionExtension(
                        $manager,
                        $imageVersion,
                        $extension,
                        $extensionOptions['config']
                    );
                }
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
            PhpFixtures::class,
        ];
    }
}
