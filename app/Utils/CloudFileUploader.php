<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CloudFileUploader
{
    public static function uploadWithResize(string $path, object $uploadableFile, int $height, int $width, ?string $deletableFile = null): string
    {
        $fileName = uniqid() . '.' . $uploadableFile->getClientOriginalExtension();

        if (isset($deletableFile)) {

            if (Storage::disk('s3')->exists($path . $deletableFile)) {

                Storage::disk('s3')->delete($path . $deletableFile);
            }
        }

        $resizedFile = Image::make($uploadableFile)->resize($width, $height);

        $fileStream = $resizedFile->stream();

        Storage::disk('s3')->put($path . $fileName, $fileStream->__toString());

        return $fileName;
    }

    public static function fileUpload(string $path, object $uploadableFile, ?string $deletableFile = null): string
    {
        $fileFullNameWithExtension = trim($uploadableFile->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName . '__' . time() . '__' . '.' . $extension;

        // dd($fileName);

        if (isset($deletableFile)) {

            if (Storage::disk('s3')->exists($path . $deletableFile)) {

                Storage::disk('s3')->delete($path . $deletableFile);
            }
        }
        // dd($path . $fileName);
        Storage::disk('s3')->put($path . $fileName, \file_get_contents($uploadableFile));

        return $fileName;
    }

    public static function deleteFile(string $path, ?string $deletableFile = null): void
    {
        if (isset($deletableFile)) {

            if (Storage::disk('s3')->exists($path . $deletableFile)) {

                Storage::disk('s3')->delete($path . $deletableFile);
            }
        }
    }
}
