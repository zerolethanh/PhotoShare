<?php
/**
 * User: ZE
 * Date: 2016/12/01
 * Time: 1:23
 */

namespace App\SLIR;


class SLIRConfig extends \SLIR\SLIRConfigDefaults
{
    public static function init()
    {
        static::$garbageCollectDivisor = 400;
        static::$garbageCollectFileCacheMaxLifetime = 345600;
        static::$browserCacheTTL = 604800; // 7*24*60*60
        static::$pathToCacheDir = storage_path('SLIR/cache');
        static::$pathToErrorLog = storage_path('SLIR/files/slir-error-log');
        static::$documentRoot = storage_path('app/photos');
        static::$urlToSLIR = '/slir'; // Tell SLIR to listen after "/assets" route
        static::$maxMemoryToAllocate = 64;
        // This must be the last line of this function
        parent::init();
    }
}

SLIRConfig::init();