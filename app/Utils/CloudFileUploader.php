<?php

namespace App\Utils;

use App\Utils\FilePath;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class CloudFileUploader
{
    public static function uploadWithResize(string $fileType, object $uploadableFile, int $height, int $width, ?string $deletableFile = null): string
    {
        $path = FilePath::paths(fileType: $fileType);

        $fileName = uniqid() . '.' . $uploadableFile->getClientOriginalExtension();

        if (isset($deletableFile)) {

            if (Storage::disk(config('file_disk.name'))->exists($path . $deletableFile)) {

                Storage::disk(config('file_disk.name'))->delete($path . $deletableFile);
            }
        }

        $resizedFile = Image::make($uploadableFile)->resize($width, $height);

        $fileStream = $resizedFile->stream();

        Storage::disk(config('file_disk.name'))->put($path . $fileName, $fileStream->__toString());

        return $fileName;
    }

    public static function fileUpload(string $fileType, object $uploadableFile, ?string $deletableFile = null): string
    {
        $path = FilePath::paths(fileType: $fileType);

        $fileFullNameWithExtension = trim($uploadableFile->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName . '__' . strtoupper(Str::random(9)) . '__' . '.' . $extension;

        if (isset($deletableFile)) {

            if (Storage::disk(config('file_disk.name'))->exists($path . $deletableFile)) {

                Storage::disk(config('file_disk.name'))->delete($path . $deletableFile);
            }
        }
        // dd($path . $fileName);
        Storage::disk(config('file_disk.name'))->put($path . $fileName, \file_get_contents($uploadableFile));

        return $fileName;
    }

    public static function deleteFile(string $fileType, ?string $deletableFile = null): void
    {
        $path = FilePath::paths(fileType: $fileType);

        if (isset($deletableFile)) {

            if (Storage::disk(config('file_disk.name'))->exists($path . $deletableFile)) {

                Storage::disk(config('file_disk.name'))->delete($path . $deletableFile);
            }
        }
    }

    public static function isFileExists(string $fileType, ?string $fileName = null): bool
    {
        $path = FilePath::paths(fileType: $fileType);

        if ($fileName) {

            if (Storage::disk(config('file_disk.name'))->exists($path . $fileName)) {

                return true;
            } else {

                return false;
            }
        } else {

            return false;
        }
    }
}
