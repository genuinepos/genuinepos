<?php

namespace App\Utils;

use App\Utils\CloudFileUploader;
use App\Utils\LocalFileUploader;

class FileUploader
{
    public static function uploadWithResize(string $fileType, object $uploadableFile, int $height, int $width, ?string $deletableFile = null): string
    {
        if (config('file_disk.name') == 'local') {

            return LocalFileUploader::uploadWithResize(fileType: $fileType, uploadableFile: $uploadableFile, height: $height, width: $width, deletableFile: $deletableFile);
        } else {

            return CloudFileUploader::uploadWithResize(fileType: $fileType, uploadableFile: $uploadableFile, height: $height, width: $width, deletableFile: $deletableFile);
        }
    }

    public static function fileUpload(string $fileType, object $uploadableFile, ?string $deletableFile = null): string
    {
        if (config('file_disk.name') == 'local') {

            return LocalFileUploader::fileUpload(fileType: $fileType, uploadableFile: $uploadableFile, deletableFile: $deletableFile);
        } else {

            return CloudFileUploader::fileUpload(fileType: $fileType, uploadableFile: $uploadableFile, deletableFile: $deletableFile);
        }
    }

    public static function deleteFile(string $fileType, ?string $deletableFile = null): void
    {
        if (config('file_disk.name') == 'local') {

            LocalFileUploader::deleteFile(fileType: $fileType, deletableFile: $deletableFile);
        } else {

            CloudFileUploader::deleteFile(fileType: $fileType, deletableFile: $deletableFile);
        }
    }
}
