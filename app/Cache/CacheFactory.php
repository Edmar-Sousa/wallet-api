<?php

declare(strict_types=1);

namespace App\Cache;

use App\Enums\CacheType;
use App\Interfaces\CacheInterface;
use RuntimeException;


class CacheFactory
{

    /**
     * Factory to create client cache
     * 
     * @param \App\Enums\CacheType $cacheType
     * 
     * @throws \RuntimeException
     * @return CacheInterface
     */
    public static function create(CacheType $cacheType): CacheInterface
    {

        switch ($cacheType) {
            case CacheType::REDIS: return new CacheRedis();

            default: throw new RuntimeException('Cache method not supported');
        }

    }

}
