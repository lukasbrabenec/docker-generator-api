<?php

namespace App\DataFixtures;

use App\Entity\Extension;
use App\Entity\Group;
use App\Entity\Image;
use App\Entity\ImagePort;
use App\Entity\ImageVersion;
use App\Entity\ImageVersionExtension;
use App\Entity\ImageVolume;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhpFixtures extends Fixture
{
    const PHP_VERSIONS_EXTENSION_EXCLUDE_MAP = [
        '5.6-apache' => [
            'apcu_bc',
            'cmark',
            'decimal',
            'ffi',
            'opencensus',
            'parallel',
            'pcov',
            'pdo_sqlsrv',
            'snuffleupagus',
            'sqlsrv',
            'tdlib',
            ''
        ],
        '7-apache' => [
            'ffi',
            'mongo',
            'mssql',
            'mysql',
            'parallel',
            'sybase_ct'
        ],
        '7.1-apache' => [
            'ffi',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'sybase_ct'
        ],
        '7.2-apache' => [
            'ffi',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'sybase_ct'
        ],
        '7.3-apache' => [
            'ffi',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'sybase_ct'
        ],
        '7.4-apache' => [
            'interbase',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'recode',
            'sybase_ct',
            'wddx'
        ],
    ];

    const PHP_EXTENSIONS_CONFIG_MAP = [
        'amqp' => '',
        'apcu' => '',
        'apcu_bc' => '',
        'bcmath' => '',
        'bz2' => '',
        'calendar' => '',
        'cmark' => '',
        'dba' => '',
        'decimal' => '',
        'enchant' => '',
        'exif' => '',
        'ffi' => '',
        'gd' => '',
        'gettext' => '',
        'gmagick' => '',
        'gmp' => '',
        'grpc' => '',
        'http' => '',
        'igbinary' => '',
        'imagick' => '',
        'imap' => '',
        'interbase' => '',
        'intl' => '',
        'ldap' => '',
        'mailparse' => '',
        'mcrypt' => '',
        'memcache' => '',
        'memcached' => '',
        'mongo' => '',
        'mongodb' => '',
        'msgpack' => '',
        'mssql' => '',
        'mysql' => '',
        'mysqli' => '',
        'oauth' => '',
        'odbc' => '',
        'opcache' => '',
        'opencensus' => '',
        'parallel' => '',
        'pcntl' => '',
        'pcov' => '',
        'pdo_dblib' => '',
        'pdo_firebird' => '',
        'pdo_mysql' => '',
        'pdo_odbc' => '',
        'pdo_pgsql' => '',
        'pdo_sqlsrv' => '',
        'pgsql' => '',
        'propro' => '',
        'protobuf' => '',
        'pspell' => '',
        'pthreads' => '',
        'raphf' => '',
        'rdkafka' => '',
        'recode' => '',
        'redis' => '',
        'shmop' => '',
        'snmp' => '',
        'snuffleupagus' => '',
        'sockets' => '',
        'solr' => '',
        'sqlsrv' => '',
        'ssh2' => '',
        'sybase_ct' => '',
        'sysvmsg' => '',
        'sysvsem' => '',
        'sysvshm' => '',
        'tdlib' => '',
        'tidy' => '',
        'timezonedb' => '',
        'uopz' => '',
        'uuid' => '',
        'wddx' => '',
        'xdebug' => '',
        'xhprof' => '',
        'xmlrpc' => '',
        'xsl' => '',
        'yaml' => '',
        'zip' => '',
        'zookeeper' => '',
    ];

    const GENERAL_EXTENSIONS_CONFIG_MAP = [
        'git' => ''
    ];

    const VOLUMES = [
        './src' => '/var/www/html'
    ];

    public function load(ObjectManager $manager)
    {
        $group = new Group();
        $group->setName('PHP');
        $manager->persist($group);

        $image = new Image();
        $image->setGroup($group);
        $image->setName('PHP');
        $image->setCode('php');
        $image->setDockerfileLocation('./src/build/');
        $manager->persist($image);

        foreach (self::PHP_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
            $extension = new Extension();
            $extension->setName($extensionName);
            $extension->setSpecial(true);
            $manager->persist($extension);
        }
        foreach (self::GENERAL_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
            $extension = new Extension();
            $extension->setName($extensionName);
            $extension->setSpecial(false);
            $manager->persist($extension);
        }
        $manager->flush();

        foreach (self::PHP_VERSIONS_EXTENSION_EXCLUDE_MAP as $version => $extensionExclude) {
            $imageVersion = new ImageVersion();
            $imageVersion->setVersion($version);
            $imageVersion->setImage($image);

            foreach (self::PHP_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
                if (!in_array($extensionName, $extensionExclude)) {
                    /** @var Extension $extension */
                    $extension = $manager->getRepository(Extension::class)->findOneBy(['name' => $extensionName]);
                    if (is_object($extension)) {
                        $imageVersionExtension = new ImageVersionExtension();
                        $imageVersionExtension->setImageVersion($imageVersion);
                        $imageVersionExtension->setExtension($extension);
                        $imageVersionExtension->setConfig($extensionConfig);
                        $manager->persist($imageVersionExtension);
                    } else {
                        throw new \Exception("extension doesnt exist");
                    }
                }
            }
            foreach (self::GENERAL_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
                if (!in_array($extensionName, $extensionExclude)) {
                    /** @var Extension $extension */
                    $extension = $manager->getRepository(Extension::class)->findOneBy(['name' => $extensionName]);
                    if (is_object($extension)) {
                        $imageVersionExtension = new ImageVersionExtension();
                        $imageVersionExtension->setImageVersion($imageVersion);
                        $imageVersionExtension->setExtension($extension);
                        $imageVersionExtension->setConfig($extensionConfig);
                        $manager->persist($imageVersionExtension);
                    } else {
                        throw new \Exception("extension doesnt exist");
                    }
                }
            }

            $manager->persist($imageVersion);

            $imagePort = new ImagePort();
            $imagePort->setImageVersion($imageVersion);
            $imagePort->setInward(80);
            $imagePort->setOutward(80);
            $manager->persist($imagePort);

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $imageVolume = new ImageVolume();
                $imageVolume->setImageVersion($imageVersion);
                $imageVolume->setHostPath($hostPath);
                $imageVolume->setContainerPath($containerPath);
                $manager->persist($imageVolume);
            }
        }
        $manager->flush();
    }
}
