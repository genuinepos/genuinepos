<?php

namespace App\Utils;

class FilePath
{
    public static function paths(string $fileType): string
    {
        $arr = [
            'businessLogo' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'business_logo/' : tenant('id') . '/' . 'business_logo/',
            'branchLogo' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'branch_logo/' : tenant('id') . '/' . 'branch_logo/',
            'category' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'categories/' : tenant('id') . '/' . 'categories/',
            'brand' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'brands/' : tenant('id') . '/' . 'brands/',
            'productThumbnail' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'products/thumbnails/' : tenant('id') . '/' . 'products/thumbnails/',
            'productVariant' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'products/variant_images/' : tenant('id') . '/' . 'products/variant_images/',
            'jobCardDocument' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'services/documents/' : tenant('id') . '/' . 'services/documents/',
            'workspaceAttachment' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'workspace_attachments/' : tenant('id') . '/' . 'workspace_attachments/',
            'advertisementAttachment' => config('file_disk.name') == 'local' ? 'uploads/' . tenant('id') . '/' . 'advertisement_attachments/' : tenant('id') . '/' . 'advertisement_attachments/',
        ];

        return $arr[$fileType];
    }
}
