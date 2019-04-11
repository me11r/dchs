<?php

namespace App\Services\QueuedReports;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Store;

/**
 * Class ReportsCacheManager
 * @package App\Services\QueuedReports
 */
class ReportsCacheManager
{

    /**
     * @var \Illuminate\Cache\Repository
     */
    private $cache;

    /**
     * ReportsCacheManager constructor.
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        $this->cache = $cacheManager->driver('reports_cache');
    }

    /**
     * @param string $key
     * @param callable $function
     * @return mixed
     */
    public function rememberForever(string $key, callable $function)
    {
        return $this->cache->rememberForever($key, $function);
    }

    public function get(string $key, $default = null)
    {
        return $this->cache->get($key, $default);
    }
}