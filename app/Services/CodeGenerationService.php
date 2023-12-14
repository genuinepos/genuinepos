<?php

namespace App\Services;

use App\Interfaces\CodeGenerationServiceInterface;
use Illuminate\Support\Facades\DB;

class CodeGenerationService implements CodeGenerationServiceInterface
{
    public function generate(string $table, string $column = 'code', string $prefix = '', int $digits = 4, int $size = 13, string $splitter = '-', string $suffixSeparator = ''): string
    {
        $entryRaw = DB::table($table)->whereNotNull($column)->orderBy($column, 'desc')->first(["$column"]);

        $prefix = strlen($prefix) === 0 ? strtoupper(substr($table, 0, 3)) : $prefix;
        $dateTimeStrPrefix = date('ymd');
        $splitterLength = strlen($splitter);
        $dateTimeStrPrefixLength = strlen($dateTimeStrPrefix);
        $prefixLength = strlen($prefix);
        $suffixSeparatorLength = strlen($suffixSeparator);
        $minSize = $prefixLength + $splitterLength + $dateTimeStrPrefixLength + $digits;
        $size = ($size < $minSize) ? $minSize : $size;
        $lastDigitsNextValue = 1;

        if (isset($entryRaw)) {
            $entry = $entryRaw->$column;
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            unset($splitterSplittedArray[0]);
            $suffixStr = implode('', $splitterSplittedArray);
            $previousDateDigits = substr($suffixStr, 0, 6);
            $serial = substr($suffixStr, 6);
            if ($dateTimeStrPrefix === $previousDateDigits) {
                $lastDigitsNextValue = intval($serial) + 1;
            }
        }

        $lastDigitsLength = ($size - ($prefixLength + $dateTimeStrPrefixLength)) - 1;
        $lastDigitsFinal = str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
        $finalSuffix = $dateTimeStrPrefix.$suffixSeparator.$lastDigitsFinal;
        $finalStr = $prefix.$splitter.$finalSuffix;

        return $finalStr;
    }

    public function generateMonthWise(string $table, string $column = 'code', string $prefix = '', int $digits = 4, int $size = 13, string $splitter = '-', string $suffixSeparator = '', $branchId = null): string
    {
        $entryRaw = DB::table($table)
            ->where('branch_id', $branchId)
            ->whereNotNull($column)
            ->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
            // ->orderByRaw("CAST((SUBSTRING_INDEX(`$column`, '-', -1)) as UNSIGNED) DESC")
            ->first(["$column"]);

        $prefix = strlen($prefix) === 0 ? strtoupper(substr($table, 0, 3)) : $prefix;
        $dateTimeStrPrefix = date('ym');
        $prefixLength = strlen($prefix);
        $splitterLength = strlen($splitter);
        $dateTimeStrPrefixLength = strlen($dateTimeStrPrefix);
        $suffixSeparatorLength = strlen($suffixSeparator);
        $minSize = $prefixLength + $splitterLength + $dateTimeStrPrefixLength + $digits;
        // $size = ($minSize < $size) ? $minSize : $size;
        $size = $minSize;
        $lastDigitsNextValue = 1;

        if (isset($entryRaw)) {

            $entry = trim($entryRaw->$column);
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[2];
            $previousMonthDigits = substr($splitterSplittedArray[1], -2);
            $currentMonthDigit = date('m');

            if (intval($currentMonthDigit) === intval($previousMonthDigits)) {
                $lastDigitsNextValue = intval($serial) + 1;
            }
        }

        $lastDigitsLength = ($size - ($prefixLength + $dateTimeStrPrefixLength)) - 1;
        $lastDigitsFinal = str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
        $finalSuffix = $dateTimeStrPrefix.$suffixSeparator.$lastDigitsFinal;
        $finalStr = $prefix.$splitter.$finalSuffix;

        return $finalStr;
    }

    public function generateMonthAndTypeWise(string $table, string $column, string $typeColName, string $typeValue = null, string $prefix = '', int $digits = 4, int $size = 13, string $splitter = '-', string $suffixSeparator = '', $branchId = null): string
    {
        $entryRaw = DB::table($table)
            ->whereNotNull($column)
            ->where('branch_id', $branchId)
            ->where($typeColName, $typeValue)
            ->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
            // ->orderByRaw("CAST((SUBSTRING_INDEX(`$column`, '-', -1)) as UNSIGNED) DESC")
            ->first(["$column"]);

        $prefix = strlen($prefix) === 0 ? strtoupper(substr($table, 0, 3)) : $prefix;
        $dateTimeStrPrefix = date('ym');
        $prefixLength = strlen($prefix);
        $splitterLength = strlen($splitter);
        $dateTimeStrPrefixLength = strlen($dateTimeStrPrefix);
        $suffixSeparatorLength = strlen($suffixSeparator);
        $minSize = $prefixLength + $splitterLength + $dateTimeStrPrefixLength + $digits;
        $size = $minSize;
        $lastDigitsNextValue = 1;

        if (isset($entryRaw)) {
            $entry = trim($entryRaw->{$column});
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[2];
            $previousMonthDigits = substr($splitterSplittedArray[1], -2);
            $currentMonthDigit = date('m');

            if (intval($currentMonthDigit) === intval($previousMonthDigits)) {
                $lastDigitsNextValue = intval($serial) + 1;
            }
        }

        $lastDigitsLength = ($size - ($prefixLength + $dateTimeStrPrefixLength)) - 1;
        $lastDigitsFinal = str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
        $finalSuffix = $dateTimeStrPrefix.$suffixSeparator.$lastDigitsFinal;
        $finalStr = $prefix.$splitter.$finalSuffix;

        return $finalStr;
    }

    public function generateAndTypeWiseWithoutYearMonth(string $table, string $column, string $typeColName, string $typeValue = null, string $prefix = '', int $digits = 3, int $size = 13, string $splitter = '-', string $suffixSeparator = ''): string
    {
        $entryRaw = DB::table($table)
            ->whereNotNull($column)
            ->where($typeColName, $typeValue)
            ->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
            // ->orderByRaw("CAST((SUBSTRING_INDEX(`$column`, '-', -1)) as UNSIGNED) DESC")
            ->first(["$column"]);

        $prefix = strlen($prefix) === 0 ? strtoupper(substr($table, 0, 3)) : $prefix;
        //$dateTimeStrPrefix = date('ym');
        $prefixLength = strlen($prefix);
        $splitterLength = strlen($splitter);
        //$dateTimeStrPrefixLength = strlen($dateTimeStrPrefix);
        $suffixSeparatorLength = strlen($suffixSeparator);
        $minSize = $prefixLength + $splitterLength + $digits;
        // $minSize = $prefixLength + $splitterLength + $dateTimeStrPrefixLength + $digits;
        $size = $minSize;
        $lastDigitsNextValue = 1;

        if (isset($entryRaw)) {

            $entry = trim($entryRaw->{$column});
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[1];
            $lastDigitsNextValue = intval($serial) + 1;
        }

        $lastDigitsLength = ($size - ($prefixLength)) - 1;
        // $lastDigitsLength = ($size - ($prefixLength + $dateTimeStrPrefixLength)) - 1;
        $lastDigitsFinal = str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
        $finalSuffix = $suffixSeparator.$lastDigitsFinal;
        // $finalSuffix = $dateTimeStrPrefix.$suffixSeparator.$lastDigitsFinal;
        $finalStr = $prefix.$splitter.$finalSuffix;

        return $finalStr;
    }

    public function generateWithoutYearMonth(string $table, string $column, string $prefix = '', int $digits = 3, int $size = 13, string $splitter = '-', string $suffixSeparator = '', ?int $branchId = null): string
    {
        $entryRaw = DB::table($table)
            ->where('branch_id', $branchId)
            ->whereNotNull($column)
            ->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
            // ->orderByRaw("CAST((SUBSTRING_INDEX(`$column`, '-', -1)) as UNSIGNED) DESC")
            ->first(["$column"]);

        $prefix = strlen($prefix) === 0 ? strtoupper(substr($table, 0, 3)) : $prefix;
        $prefixLength = strlen($prefix);
        $splitterLength = strlen($splitter);
        $suffixSeparatorLength = strlen($suffixSeparator);
        $minSize = $prefixLength + $splitterLength + $digits;
        $size = $minSize;
        $lastDigitsNextValue = 1;

        if (isset($entryRaw)) {

            $entry = trim($entryRaw->{$column});
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[1];
            $lastDigitsNextValue = intval($serial) + 1;
        }

        $lastDigitsLength = ($size - ($prefixLength)) - 1;
        $lastDigitsFinal = str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
        $finalSuffix = $suffixSeparator.$lastDigitsFinal;
        $finalStr = $prefix.$splitter.$finalSuffix;

        return $finalStr;
    }
}
