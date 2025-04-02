<?php

namespace Config;

use CodeIgniter\Cache\Handlers\FileHandler;
use CodeIgniter\Cache\Handlers\DummyHandler;
use CodeIgniter\Cache\CacheInterface;

class Cache extends \CodeIgniter\Config\BaseConfig
{
    public string $handler = 'file';
    public string $backupHandler = 'dummy';
    public int $ttl = 60;

    /**
     * Path where to save cache files.
     */
    public string $file = WRITEPATH . 'cache/';

    /**
     * Cache prefix.
     */
    public string $prefix = '';

    /**
     * Subdirectory prefix for cache files.
     */
    public string $path = '';

    /**
     * File permissions for cache files.
     */
    public int $filePermissions = 0640;

    /**
     * Maximum cache file size.
     */
    public int $maxSize = 4096;

    /**
     * Valid cache handlers.
     * Make sure the class specified exists and can be loaded.
     */
    public array $validHandlers = [
        'dummy' => DummyHandler::class,
        'file'  => FileHandler::class,
    ];

    /**
     * Cache Query String
     * Whether to take the URL query string into consideration when generating
     * output cache files. Valid options are:
     *    false      = Disabled
     *    true       = Enabled, take all query parameters into account.
     *                 Please be aware that this may result in numerous cache
     *                 files generated for the same page over and over again.
     *    array('q') = Enabled, but only take into account the specified list
     *                 of query parameters.
     *
     * @var bool|string[]
     */
    public $cacheQueryString = false;
}
