<?php

namespace App\Services;

class PrintPageSizeService
{
    public static function pageSizeName(int $index, bool $fullLine = true): string
    {
        $arr = [
            1 => $fullLine ? __('A4 Page | Height 11.7Incs, Width: 8.3Incs') : __('A4'),
            2 => $fullLine ? __('A5 Page | Height 8.3Incs, Width: 5.8Incs') : __('A5'),
            3 => $fullLine ? __('POS Printer | Width: 3Incs') : __('POS Print'),
        ];

        return $arr[$index];
    }
}
