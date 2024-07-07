<?php

namespace App\Utils;

use Exception;
use App\Utils\FilePath;
use Intervention\Image\Facades\Image;

class LocalFileUploader
{
    public static function uploadWithResize(string $fileType, object $uploadableFile, int $height, int $width, ?string $deletableFile = null): string
    {
        $path = FilePath::paths(fileType: $fileType);

        $fileName = uniqid() . '.' . $uploadableFile->getClientOriginalExtension();

        if ($deletableFile) {

            if (file_exists($path . $deletableFile)) {

                unlink($path . $deletableFile);
            }
        }

        if (!\File::isDirectory($path)) {

            \File::makeDirectory($path, 493, true);
        }

        Image::make($uploadableFile)->resize($width, $height)->save($path . '/' . $fileName);
        return $fileName;
    }

    public static function fileUpload(string $fileType, object $uploadableFile, ?string $deletableFile = null): string
    {
        $path = FilePath::paths(fileType: $fileType);

        $fileFullNameWithExtension = trim($uploadableFile->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName . '__' . time() . '__' . '.' . $extension;

        if ($deletableFile) {

            if (file_exists($path . $deletableFile)) {

                unlink($path . $deletableFile);
            }
        }

        if (!\File::isDirectory($path)) {

            \File::makeDirectory($path, 493, true);
        }

        $uploadableFile->move($path, $fileName);

        return $fileName;
    }

    public static function deleteFile(string $fileType, ?string $deletableFile = null): void
    {
        $path = FilePath::paths(fileType: $fileType);

        if (isset($deletableFile)) {

            if (file_exists($path . $deletableFile)) {

                unlink($path . $deletableFile);
            }
        }
    }

    public static function uploadWithFullPath(object $file, string $filePath = 'uploads/'): string
    {
        if (!file_exists($filePath)) {
            try {
                mkdir($filePath);
            } catch (Exception $e) {
            }
        }

        $fileFullNameWithExtension = trim($file->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName . '__' . uniqid() . '__' . '.' . $extension;
        $file->move($filePath, $fileName);
        $fullPathToStoreInDb = "{$filePath}/{$fileName}";

        return $fullPathToStoreInDb;
    }

    public static function upload(object $file, string $filePath = 'uploads/'): string
    {
        if (!file_exists($filePath)) {
            try {
                mkdir($filePath);
            } catch (Exception $e) {
            }
        }

        $fileFullNameWithExtension = trim($file->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName . '__' . uniqid() . '__' . '.' . $extension;
        // $file->move(public_path($filePath), $fileName);
        $file->move($filePath, $fileName);

        // \Log::info($filePath);
        // \Log::info($fileName);
        return $fileName;
    }

    public static function uploadMultiple(?array $files, string $filesPath = 'uploads/'): ?string
    {
        if (isset($files)) {
            if (!file_exists($filesPath)) {
                try {
                    mkdir($filesPath);
                } catch (Exception $e) {
                }
            }
            $filesNameArr = [];
            foreach ($files as $key => $file) {
                $fileFullNameWithExtension = trim($file->getClientOriginalName());
                $arr = preg_split('/\./', $fileFullNameWithExtension);
                $extension = array_pop($arr);
                $fullName = implode('.', $arr);
                $fileName = $fullName . '__' . uniqid() . '__' . '.' . $extension;
                $file->move($filesPath, $fileName);
                $filesNameArr[$key] = $fileName;
            }

            return json_encode($filesNameArr);
        }

        return '';
    }

    public static function uploadThumbnail(object $file, ?string $filePath = 'uploads/', ?int $width = 250, ?int $height = 250): string
    {
        if (!file_exists($filePath)) {
            try {
                mkdir($filePath);
            } catch (Exception $e) {
            }
        }
        $fileFullNameWithExtension = trim($file->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName . '__' . uniqid() . '__' . '.' . $extension;
        $file->move($filePath, $fileName);

        return $fileName;
    }
}
