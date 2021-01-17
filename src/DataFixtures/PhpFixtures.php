<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class PhpFixtures extends BaseFixtures
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

    const SPECIAL_EXTENSIONS_CONFIG_MAP = [
        'amqp' => null,
        'apcu' => null,
        'apcu_bc' => null,
        'bcmath' => null,
        'bz2' => null,
        'calendar' => null,
        'cmark' => null,
        'dba' => null,
        'decimal' => null,
        'enchant' => null,
        'exif' => null,
        'ffi' => null,
        'gd' => null,
        'gettext' => null,
        'gmagick' => null,
        'gmp' => null,
        'grpc' => null,
        'http' => null,
        'igbinary' => null,
        'imagick' => null,
        'imap' => null,
        'interbase' => null,
        'intl' => null,
        'ldap' => null,
        'mailparse' => null,
        'mcrypt' => null,
        'memcache' => null,
        'memcached' => null,
        'mongo' => null,
        'mongodb' => null,
        'msgpack' => null,
        'mssql' => null,
        'mysql' => null,
        'mysqli' => null,
        'oauth' => null,
        'odbc' => null,
        'opcache' => null,
        'opencensus' => null,
        'parallel' => null,
        'pcntl' => null,
        'pcov' => null,
        'pdo_dblib' => null,
        'pdo_firebird' => null,
        'pdo_mysql' => null,
        'pdo_odbc' => null,
        'pdo_pgsql' => null,
        'pdo_sqlsrv' => null,
        'pgsql' => null,
        'propro' => null,
        'protobuf' => null,
        'pspell' => null,
        'pthreads' => null,
        'raphf' => null,
        'rdkafka' => null,
        'recode' => null,
        'redis' => null,
        'shmop' => null,
        'snmp' => null,
        'snuffleupagus' => null,
        'sockets' => null,
        'solr' => null,
        'sqlsrv' => null,
        'ssh2' => null,
        'sybase_ct' => null,
        'sysvmsg' => null,
        'sysvsem' => null,
        'sysvshm' => null,
        'tdlib' => null,
        'tidy' => null,
        'timezonedb' => null,
        'uopz' => null,
        'uuid' => null,
        'wddx' => null,
        'xdebug' => null,
        'xhprof' => null,
        'xmlrpc' => null,
        'xsl' => null,
        'yaml' => null,
        'zip' => null,
        'zookeeper' => null,
    ];

    const GENERAL_EXTENSIONS_CONFIG_MAP = [
        'git' => null
    ];

    const PORTS = [
        80 => 80
    ];

    const VOLUMES = [
        './php' => '/var/www/html'
    ];

    /**
     * @param ObjectManager $manager
     * @throws Exception\FixturesException
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->_getOrCreateImage($manager, 'PHP', 'php', './php/');

        foreach (self::SPECIAL_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
            $this->_createExtension($manager, $extensionName, true);
        }
        foreach (self::GENERAL_EXTENSIONS_CONFIG_MAP as $extensionName => $extensionConfig) {
            $this->_createExtension($manager, $extensionName, false);
        }
        $manager->flush();

        foreach (self::PHP_VERSIONS_EXTENSION_EXCLUDE_MAP as $version => $extensionExclude) {
            $imageVersion = $this->_createImageVersion($manager, $image, $version);

            foreach (array_merge(self::SPECIAL_EXTENSIONS_CONFIG_MAP, self::GENERAL_EXTENSIONS_CONFIG_MAP) as $extensionName => $extensionConfig) {
                if (!in_array($extensionName, $extensionExclude)) {
                    $extension = $this->_getExtension($manager, $extensionName);
                    $this->_createImageVersionExtension($manager, $imageVersion, $extension, $extensionConfig);
                }
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->_createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $this->_createImageVolume($manager, $imageVersion, $hostPath, $containerPath);
            }
        }
        $manager->flush();
    }
}
