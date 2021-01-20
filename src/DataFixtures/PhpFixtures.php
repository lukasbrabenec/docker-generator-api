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
            'sybase_ct',
        ],
        '7.1-apache' => [
            'ffi',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'sybase_ct',
        ],
        '7.2-apache' => [
            'ffi',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'sybase_ct',
        ],
        '7.3-apache' => [
            'ffi',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'sybase_ct',
        ],
        '7.4-apache' => [
            'interbase',
            'mongo',
            'mssql',
            'mysql',
            'pthreads',
            'recode',
            'sybase_ct',
            'wddx',
        ],
    ];

    const SPECIAL_EXTENSIONS_OPTIONS_MAP = [
        'amqp' => [
            'customCommand' => null,
            'config' => null,
        ],
        'apcu' => [
            'customCommand' => null,
            'config' => null,
        ],
        'apcu_bc' => [
            'customCommand' => null,
            'config' => null,
        ],
        'bcmath' => [
            'customCommand' => null,
            'config' => null,
        ],
        'bz2' => [
            'customCommand' => null,
            'config' => null,
        ],
        'calendar' => [
            'customCommand' => null,
            'config' => null,
        ],
        'cmark' => [
            'customCommand' => null,
            'config' => null,
        ],
        'dba' => [
            'customCommand' => null,
            'config' => null,
        ],
        'decimal' => [
            'customCommand' => null,
            'config' => null,
        ],
        'enchant' => [
            'customCommand' => null,
            'config' => null,
        ],
        'exif' => [
            'customCommand' => null,
            'config' => null,
        ],
        'ffi' => [
            'customCommand' => null,
            'config' => null,
        ],
        'gd' => [
            'customCommand' => null,
            'config' => null,
        ],
        'gettext' => [
            'customCommand' => null,
            'config' => null,
        ],
        'gmagick' => [
            'customCommand' => null,
            'config' => null,
        ],
        'gmp' => [
            'customCommand' => null,
            'config' => null,
        ],
        'grpc' => [
            'customCommand' => null,
            'config' => null,
        ],
        'http' => [
            'customCommand' => null,
            'config' => null,
        ],
        'igbinary' => [
            'customCommand' => null,
            'config' => null,
        ],
        'imagick' => [
            'customCommand' => null,
            'config' => null,
        ],
        'imap' => [
            'customCommand' => null,
            'config' => null,
        ],
        'interbase' => [
            'customCommand' => null,
            'config' => null,
        ],
        'intl' => [
            'customCommand' => null,
            'config' => null,
        ],
        'ldap' => [
            'customCommand' => null,
            'config' => null,
        ],
        'mailparse' => [
            'customCommand' => null,
            'config' => null,
        ],
        'mcrypt' => [
            'customCommand' => null,
            'config' => null,
        ],
        'memcache' => [
            'customCommand' => null,
            'config' => null,
        ],
        'memcached' => [
            'customCommand' => null,
            'config' => null,
        ],
        'mongo' => [
            'customCommand' => null,
            'config' => null,
        ],
        'mongodb' => [
            'customCommand' => null,
            'config' => null,
        ],
        'msgpack' => [
            'customCommand' => null,
            'config' => null,
        ],
        'mssql' => [
            'customCommand' => null,
            'config' => null,
        ],
        'mysql' => [
            'customCommand' => null,
            'config' => null,
        ],
        'mysqli' => [
            'customCommand' => null,
            'config' => null,
        ],
        'oauth' => [
            'customCommand' => null,
            'config' => null,
        ],
        'odbc' => [
            'customCommand' => null,
            'config' => null,
        ],
        'opcache' => [
            'customCommand' => null,
            'config' => null,
        ],
        'opencensus' => [
            'customCommand' => null,
            'config' => null,
        ],
        'parallel' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pcntl' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pcov' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pdo_dblib' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pdo_firebird' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pdo_mysql' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pdo_odbc' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pdo_pgsql' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pdo_sqlsrv' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pgsql' => [
            'customCommand' => null,
            'config' => null,
        ],
        'propro' => [
            'customCommand' => null,
            'config' => null,
        ],
        'protobuf' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pspell' => [
            'customCommand' => null,
            'config' => null,
        ],
        'pthreads' => [
            'customCommand' => null,
            'config' => null,
        ],
        'raphf' => [
            'customCommand' => null,
            'config' => null,
        ],
        'rdkafka' => [
            'customCommand' => null,
            'config' => null,
        ],
        'recode' => [
            'customCommand' => null,
            'config' => null,
        ],
        'redis' => [
            'customCommand' => null,
            'config' => null,
        ],
        'shmop' => [
            'customCommand' => null,
            'config' => null,
        ],
        'snmp' => [
            'customCommand' => null,
            'config' => null,
        ],
        'snuffleupagus' => [
            'customCommand' => null,
            'config' => null,
        ],
        'sockets' => [
            'customCommand' => null,
            'config' => null,
        ],
        'solr' => [
            'customCommand' => null,
            'config' => null,
        ],
        'sqlsrv' => [
            'customCommand' => null,
            'config' => null,
        ],
        'ssh2' => [
            'customCommand' => null,
            'config' => null,
        ],
        'sybase_ct' => [
            'customCommand' => null,
            'config' => null,
        ],
        'sysvmsg' => [
            'customCommand' => null,
            'config' => null,
        ],
        'sysvsem' => [
            'customCommand' => null,
            'config' => null,
        ],
        'sysvshm' => [
            'customCommand' => null,
            'config' => null,
        ],
        'tdlib' => [
            'customCommand' => null,
            'config' => null,
        ],
        'tidy' => [
            'customCommand' => null,
            'config' => null,
        ],
        'timezonedb' => [
            'customCommand' => null,
            'config' => null,
        ],
        'uopz' => [
            'customCommand' => null,
            'config' => null,
        ],
        'uuid' => [
            'customCommand' => null,
            'config' => null,
        ],
        'wddx' => [
            'customCommand' => null,
            'config' => null,
        ],
        'xdebug' => [
            'customCommand' => null,
            'config' => null,
        ],
        'xhprof' => [
            'customCommand' => null,
            'config' => null,
        ],
        'xmlrpc' => [
            'customCommand' => null,
            'config' => null,
        ],
        'xsl' => [
            'customCommand' => null,
            'config' => null,
        ],
        'yaml' => [
            'customCommand' => null,
            'config' => null,
        ],
        'zip' => [
            'customCommand' => null,
            'config' => null,
        ],
        'zookeeper' => [
            'customCommand' => null,
            'config' => null,
        ],
    ];

    const GENERAL_EXTENSIONS_OPTIONS_MAP = [
        'git' => [
            'customCommand' => null,
            'config' => null,
        ],
        'composer' => [
            'customCommand' => 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer',
            'config' => null,
        ],
    ];

    const PORTS = [
        80 => 80,
    ];

    const VOLUMES = [
        './php' => '/var/www/html',
    ];

    /**
     * @throws Exception\FixturesException
     */
    public function load(ObjectManager $manager)
    {
        $image = $this->getOrCreateImage($manager, 'PHP', 'php', './php/');

        foreach (self::SPECIAL_EXTENSIONS_OPTIONS_MAP as $extensionName => $extensionOptions) {
            $this->createExtension($manager, $extensionName, true, $extensionOptions['customCommand']);
        }
        foreach (self::GENERAL_EXTENSIONS_OPTIONS_MAP as $extensionName => $extensionOptions) {
            $this->createExtension($manager, $extensionName, false, $extensionOptions['customCommand']);
        }
        $manager->flush();

        foreach (self::PHP_VERSIONS_EXTENSION_EXCLUDE_MAP as $version => $extensionExclude) {
            $imageVersion = $this->createImageVersion($manager, $image, $version);

            foreach (array_merge(
                self::SPECIAL_EXTENSIONS_OPTIONS_MAP,
                self::GENERAL_EXTENSIONS_OPTIONS_MAP
            ) as $extensionName => $extensionOptions
            ) {
                if (!in_array($extensionName, $extensionExclude)) {
                    $extension = $this->getExtension($manager, $extensionName);
                    $this->createImageVersionExtension(
                        $manager,
                        $imageVersion,
                        $extension,
                        $extensionOptions['config']
                    );
                }
            }

            foreach (self::PORTS as $inwardPort => $outwardPort) {
                $this->createImagePort($manager, $imageVersion, $inwardPort, $outwardPort);
            }

            foreach (self::VOLUMES as $hostPath => $containerPath) {
                $this->createImageVolume($manager, $imageVersion, $hostPath, $containerPath);
            }
        }
        $manager->flush();
    }
}
