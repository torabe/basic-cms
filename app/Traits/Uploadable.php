<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StorageHelper;

trait Uploadable
{
    /**
     * 画像アップロード先のフルパス
     *
     * @var string
     */
    private $uploadPath;

    private function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    private function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * ファイルアップロード
     *
     * @param [type] $file
     * @return string filePath
     */
    private function fileUpload($file)
    {
        if (is_string($file) || $file === null) return $file;

        return $file->store($this->getUploadPath());
    }

    /**
     * ファイルの削除
     *
     * @param string $path
     * @return void
     */
    private function fileDelete(string $url = null)
    {
        if ($url === null) return;

        $path = StorageHelper::getStoragePath($url);
        Storage::delete($path);
    }
}
