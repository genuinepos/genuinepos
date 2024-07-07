<?php

if (!function_exists('file_link')) {

    function file_link(string $fileType, ?string $fileName = null): string
    {
        $path = \App\Utils\FilePath::paths(fileType: $fileType);
        if (config('file_disk.name') == 'local') {

            return asset($path . $fileName);
        } else {

            return \Illuminate\Support\Facades\Storage::disk(config('file_disk.name'))->url($path . $fileName);
        }
    }
}
