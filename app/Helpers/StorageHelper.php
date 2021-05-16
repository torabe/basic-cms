<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class StorageHelper
{
    /**
     * Undocumented function
     *
     * @param string $path
     * @return void
     */
    public static function getSymbolicLink(?string $path)
    {
        if (is_null($path)) return $path;

        return \Storage::disk(Config::get('filesystems.disks.s3.bucket') ? 's3' : 'local')->url($path);
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @return void
     */
    public static function getStoragePath(?string $url)
    {
        if (is_null($url)) return $url;

        return Config::get('filesystems.disks.s3.bucket') ? preg_replace('/http[s]?:\/\/[^\/]*/u', '', $url) : str_replace('/storage', 'public',  $url);
    }
}
