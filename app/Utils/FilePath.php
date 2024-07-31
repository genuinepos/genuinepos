<?php

namespace App\Utils;

class FilePath
{
    public static function paths(string $fileType): string
    {
        $arr = [
            'businessLogo' => config('file_disk.name') == 'local' ? 'uploads/business_logo/' : 'business_logo/',
            'branchLogo' => config('file_disk.name') == 'local' ? 'uploads/branch_logo/' : 'branch_logo/',
            'category' => config('file_disk.name') == 'local' ? 'uploads/categories/' : 'categories/',
            'brand' => config('file_disk.name') == 'local' ? 'uploads/brands/' : 'brands/',
            'productThumbnail' => config('file_disk.name') == 'local' ? 'uploads/products/thumbnails/' : 'products/thumbnails/',
            'productVariant' => config('file_disk.name') == 'local' ? 'uploads/products/variant_images/' : 'products/variant_images/',
            'user' => config('file_disk.name') == 'local' ? 'uploads/user_photos/' : 'user_photos/',
            'jobCardDocument' => config('file_disk.name') == 'local' ? 'uploads/services/documents/' : 'services/documents/',
            'workspaceAttachment' => config('file_disk.name') == 'local' ? 'uploads/workspace_attachments/' : 'workspace_attachments/',
            'advertisementAttachment' => config('file_disk.name') == 'local' ? 'uploads/advertisement_attachments/' : 'advertisement_attachments/',
        ];

        return $arr[$fileType];
    }
}
