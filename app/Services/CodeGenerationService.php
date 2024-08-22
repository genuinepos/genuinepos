<?php

namespace App\Services;

use App\Enums\CategoryType;
use App\Models\Setups\Branch;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CodeGenerationServiceInterface;

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
        $finalSuffix = $dateTimeStrPrefix . $suffixSeparator . $lastDigitsFinal;
        $finalStr = $prefix . $splitter . $finalSuffix;

        return $finalStr;
    }

    public function generateMonthWise(string $table, string $column = 'code', string $prefix = '', int $digits = 4, int $size = 13, string $splitter = '-', string $suffixSeparator = '', $branchId = null): string
    {
        $entryRaw = DB::table($table)
            ->where('branch_id', $branchId)
            ->whereNotNull($column)
            // ->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
            ->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(`$column`, '$splitter', 2), '$splitter', -1) AS UNSIGNED) DESC")
            ->orderByRaw(" CAST(SUBSTRING_INDEX(`$column`, '$splitter', -1) AS UNSIGNED) DESC")
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
        $finalSuffix = $dateTimeStrPrefix . $suffixSeparator . $lastDigitsFinal;
        $finalStr = $prefix . $splitter . $finalSuffix;

        return $finalStr;
    }

    // public function generateMonthWise2(string $table, string $column = 'code', string $prefix = '', int $digits = 4, int $size = 13, string $splitter = '-', string $suffixSeparator = '', string $dateTimePrefix = null, $branchId = null): string
    // {
    //     // Fetch the last entry for the given branch

    //     $entryRaw = DB::table($table)
    //         ->where('branch_id', $branchId)
    //         ->whereNotNull($column)
    //         ->orderByRaw("
    //     CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(`$column`, '$splitter', 2), '$splitter', -1) AS UNSIGNED) DESC
    // ")
    //         ->orderByRaw("
    //     CAST(SUBSTRING_INDEX(`$column`, '$splitter', -1) AS UNSIGNED) DESC
    // ")
    //         ->first(["$column"]);

    //     dd($entryRaw);

    //     // Set default values for prefix and date prefix
    //     $prefix = strlen($prefix) === 0 ? strtoupper(substr($table, 0, 3)) : $prefix;
    //     $dateTimeStrPrefix = $dateTimePrefix ? $dateTimePrefix : date('ym');
    //     $lastDigitsNextValue = 1;

    //     if (isset($entryRaw)) {
    //         $entry = trim($entryRaw->$column);
    //         $splitterSplittedArray = explode($splitter, $entry);
    //         $serial = end($splitterSplittedArray); // Get the last part, which is the serial number
    //         // dd($serial);
    //         $previousMonthDigits = substr($splitterSplittedArray[1], -2);
    //         $currentMonthDigit = date('m');

    //         if (intval($currentMonthDigit) === intval($previousMonthDigits)) {
    //             $lastDigitsNextValue = intval($serial) + 1;
    //         }
    //     }

    //     // Create the serial number with dynamic length
    //     $lastDigitsFinal = str_pad($lastDigitsNextValue, $digits, '0', STR_PAD_LEFT);

    //     // Generate the final string
    //     $finalStr = $prefix . $splitter . $dateTimeStrPrefix . $splitter . $lastDigitsFinal;

    //     return $finalStr;
    // }

    public function generateMonthAndTypeWise(string $table, string $column, string $typeColName, string $typeValue = null, string $prefix = '', int $digits = 4, int $size = 13, string $splitter = '-', string $suffixSeparator = '', ?int $branchId = null, ?string $dateTimePrefix = null, ?string $intVal = null): string
    {
        $entryRaw = DB::table($table)
            ->whereNotNull($column)
            ->where('branch_id', $branchId)
            ->where($typeColName, $typeValue)
            // ->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
            // ->orderByRaw("CAST((SUBSTRING_INDEX(`$column`, '-', -1)) as UNSIGNED) DESC")
            ->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(`$column`, '$splitter', 2), '$splitter', -1) AS UNSIGNED) DESC")
            ->orderByRaw(" CAST(SUBSTRING_INDEX(`$column`, '$splitter', -1) AS UNSIGNED) DESC")
            ->first(["$column"]);

        // dd($entryRaw);

        $prefix = strlen($prefix) === 0 ? strtoupper(substr($table, 0, 3)) : $prefix;
        $dateTimeStrPrefix = $dateTimePrefix ? $dateTimePrefix : date('ym');
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
            $currentMonthDigit = $intVal ? $intVal : date('m');

            if (intval($currentMonthDigit) === intval($previousMonthDigits)) {
                $lastDigitsNextValue = intval($serial) + 1;
            }
        }

        $lastDigitsLength = ($size - ($prefixLength + $dateTimeStrPrefixLength)) - 1;
        $lastDigitsFinal = str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
        $finalSuffix = $dateTimeStrPrefix . $suffixSeparator . $lastDigitsFinal;
        $finalStr = $prefix . $splitter . $finalSuffix;

        return $finalStr;
    }

    public function generateAndTypeWiseWithoutYearMonth(string $table, string $column, string $typeColName, string $typeValue = null, string $prefix = '', int $digits = 3, int $size = 13, string $splitter = '-', string $suffixSeparator = '', bool $isCheckBranch = false, ?int $branchId = null): string
    {
        $entryRaw = null;
        $query = DB::table($table)->whereNotNull($column)->where($typeColName, $typeValue);
        if ($isCheckBranch == true) {

            $query->where('branch_id', $branchId);
        }

        // $entryRaw = $query->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
        $entryRaw = $query->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(`$column`, '$splitter', 2), '$splitter', -1) AS UNSIGNED) DESC")
            ->orderByRaw(" CAST(SUBSTRING_INDEX(`$column`, '$splitter', -1) AS UNSIGNED) DESC")
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
        $finalSuffix = $suffixSeparator . $lastDigitsFinal;
        // $finalSuffix = $dateTimeStrPrefix.$suffixSeparator.$lastDigitsFinal;
        $finalStr = $prefix . $splitter . $finalSuffix;

        return $finalStr;
    }

    public function generateWithoutYearMonth(string $table, string $column, string $prefix = '', int $digits = 3, int $size = 13, string $splitter = '-', string $suffixSeparator = '', ?int $branchId = null): string
    {
        $entryRaw = DB::table($table)
            ->where('branch_id', $branchId)
            ->whereNotNull($column)
            // ->orderByRaw("SUBSTRING(`$column`, POSITION('-' IN `$column`) + 1, CHAR_LENGTH(`$column`)) DESC")
            ->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(`$column`, '$splitter', 2), '$splitter', -1) AS UNSIGNED) DESC")
            ->orderByRaw(" CAST(SUBSTRING_INDEX(`$column`, '$splitter', -1) AS UNSIGNED) DESC")
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
        $finalSuffix = $suffixSeparator . $lastDigitsFinal;
        $finalStr = $prefix . $splitter . $finalSuffix;

        return $finalStr;
    }

    public function branchCode(?int $parentBranchId = null)
    {
        $childBranchCount = 0;
        $parentBranchCode = null;
        if (isset($parentBranchId)) {

            $parentBranch = Branch::with('childBranches')->where('id', $parentBranchId)->first();

            $count = count($parentBranch->childBranches);
            $childBranchCount = $count > 0 ? ++$count : 1;
            $parentBranchCode = $parentBranch->branch_code;
        }

        $lastBranchCode = DB::table('branches')->whereNull('parent_branch_id')->orderBy('branch_code', 'desc')->first(['branch_code']);

        $differentBranchCode = isset($lastBranchCode) ? ++$lastBranchCode->branch_code : 1;

        $branchCode = str_pad($differentBranchCode, 2, '0', STR_PAD_LEFT);
        $childBranchCode = $childBranchCount > 0 ? $childBranchCount : null;
        $__childBranchCode = isset($childBranchCode) ? '/' . $childBranchCode : null;

        if (isset($parentBranchCode)) {

            return str_pad($parentBranchCode, 2, '0', STR_PAD_LEFT) . $__childBranchCode;
        } else {

            return $branchCode;
        }
    }

    public function categoryCode(int $type): ?string
    {
        $splitter = '-';
        $entryRaw = null;
        $prefix = '';
        $query = DB::table('categories');

        if ($type == CategoryType::MainCategory->value) {

            $query->whereNull('parent_category_id');
            $prefix = 'C-';
        } elseif ($type == CategoryType::Subcategory->value) {

            $prefix = 'SC-';
            $query->whereNotNull('parent_category_id');
        }

        $entryRaw = $query
            ->orderByRaw("SUBSTRING(code, POSITION('-' IN code) + 1, CHAR_LENGTH(code)) DESC")
            // ->orderByRaw("CAST((SUBSTRING_INDEX(code, '-', -1)) as UNSIGNED) DESC")
            ->first(['code']);

        $lastDigitsNextValue = 1;
        $lastDigitsLength = 3;

        if (isset($entryRaw)) {

            $entry = trim($entryRaw->code);
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[1];
            $lastDigitsNextValue = intval($serial) + 1;
        }

        return $lastDigitsFinal = $prefix . str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
        // dd($lastDigitsFinal);
    }

    public function brandCode(): ?string
    {
        $splitter = '-';
        $entryRaw = null;
        $prefix = 'B-';
        $query = DB::table('brands');

        $entryRaw = $query->orderByRaw("SUBSTRING(code, POSITION('-' IN code) + 1, CHAR_LENGTH(code)) DESC")->first(['code']);

        $lastDigitsNextValue = 1;
        $lastDigitsLength = 3;

        if (isset($entryRaw)) {

            $entry = trim($entryRaw->code);
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[1];
            $lastDigitsNextValue = intval($serial) + 1;
        }

        return $lastDigitsFinal = $prefix . str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
    }

    public function unitCode(): ?string
    {
        $splitter = '-';
        $entryRaw = null;
        $prefix = 'U-';
        $query = DB::table('units');

        $entryRaw = $query->orderByRaw("SUBSTRING(code, POSITION('-' IN code) + 1, CHAR_LENGTH(code)) DESC")->first(['code']);

        $lastDigitsNextValue = 1;
        $lastDigitsLength = 3;

        if (isset($entryRaw)) {

            $entry = trim($entryRaw->code);
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[1];
            $lastDigitsNextValue = intval($serial) + 1;
        }

        return $lastDigitsFinal = $prefix . str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
    }

    public function warrantyCode(): ?string
    {
        $splitter = '-';
        $entryRaw = null;
        $prefix = 'W-';
        $query = DB::table('warranties');

        $entryRaw = $query->orderByRaw("SUBSTRING(code, POSITION('-' IN code) + 1, CHAR_LENGTH(code)) DESC")->first(['code']);

        $lastDigitsNextValue = 1;
        $lastDigitsLength = 3;

        if (isset($entryRaw)) {

            $entry = trim($entryRaw->code);
            $splitterSplittedArray = preg_split("/([\\$splitter\-\#\*\--])/", $entry, -1, PREG_SPLIT_NO_EMPTY);
            $serial = $splitterSplittedArray[1];
            $lastDigitsNextValue = intval($serial) + 1;
        }

        return $lastDigitsFinal = $prefix . str_pad($lastDigitsNextValue, $lastDigitsLength, '0', STR_PAD_LEFT);
    }
}
