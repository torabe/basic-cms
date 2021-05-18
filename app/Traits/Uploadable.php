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
        $this->uploadPath = '/public/' . $uploadPath;
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

        return Storage::disk(Config::get('filesystems.disks.s3.bucket') ? 's3' : 'local')->putFile($this->getUploadPath(), $file, 'public');
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
        Storage::disk(Config::get('filesystems.disks.s3.bucket') ? 's3' : 'local')->delete($path);
    }

    /**
     * ディレクトリの削除
     *
     * @return void
     */
    private function deleteDirectory()
    {
        Storage::disk(Config::get('filesystems.disks.s3.bucket') ? 's3' : 'local')->deleteDirectory($this->getUploadPath());
    }
}
