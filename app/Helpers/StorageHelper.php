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
        return ($path !== null) ? asset(preg_replace('/^public/i', 'storage', $path)) : $path;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @return void
     */
    public static function getStoragePath(?string $url)
    {
        return ($url !== null) ? str_replace(Config::get('app.url') . '/storage', 'public',  $url) : $url;
    }
}
