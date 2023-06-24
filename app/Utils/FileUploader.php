<?php

namespace App\Utils;

class FileUploader
{
    public static function upload(object $file, string $filePath = 'uploads/'): string
    {
        if (! file_exists($filePath)) {
            try {
                mkdir($filePath);
            } catch (Exception $e) {
            }
        }

        $fileFullNameWithExtension = trim($file->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName.'__'.uniqid().'__'.'.'.$extension;
        $file->move($filePath, $fileName);
        \Log::debug('uploaded');

        return $fileName;
    }

    public static function uploadMultiple(?array $files, string $filesPath = 'uploads/'): ?string
    {
        if (isset($files)) {
            if (! file_exists($filesPath)) {
                try {
                    mkdir($filePath);
                } catch (Exception $e) {
                }
            }
            $filesNameArr = [];
            foreach ($files as $key => $file) {
                $fileFullNameWithExtension = trim($file->getClientOriginalName());
                $arr = preg_split('/\./', $fileFullNameWithExtension);
                $extension = array_pop($arr);
                $fullName = implode('.', $arr);
                $fileName = $fullName.'__'.uniqid().'__'.'.'.$extension;
                $file->move($filesPath, $fileName);
                $filesNameArr[$key] = $fileName;
            }

            return json_encode($filesNameArr);
        }

        return '';
    }

    public static function uploadThumbnail(object $file, ?string $filePath = 'uploads/', ?int $width = 250, ?int $height = 250): string
    {
        if (! file_exists($filePath)) {
            try {
                mkdir($filePath);
            } catch (Exception $e) {
            }
        }

        $fileFullNameWithExtension = trim($file->getClientOriginalName());
        $arr = preg_split('/\./', $fileFullNameWithExtension);
        $extension = array_pop($arr);
        $fullName = implode('.', $arr);
        $fileName = $fullName.'__'.uniqid().'__'.'.'.$extension;
        $file->move($filePath, $fileName);
        \Log::debug('uploaded');

        return $fileName;
    }
}
