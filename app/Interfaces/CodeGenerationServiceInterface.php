<?php

namespace App\Interfaces;

interface CodeGenerationServiceInterface
{
    public function generate(
        string $table,
        string $column = 'code',
        string $prefix = '',
        int $digits = 4,
        int $size = 13,
        string $splitter = '-',
        string $suffixSeparator = '',
    ): string;

    public function generateMonthWise(
        string $table,
        string $column = 'code',
        string $prefix = '',
        int $digits = 4,
        int $size = 13,
        string $splitter = '-',
        string $suffixSeparator = '',
        string $branch_id = null,
    ): string;

    public function generateMonthAndTypeWise(
        string $table,
        string $column,
        string $typeColName,
        string $typeValue = null,
        string $prefix = '',
        int $digits = 4,
        int $size = 13,
        string $splitter = '-',
        string $suffixSeparator = '',
        string $branch_id = null,
    ): string;

    public function generateAndTypeWiseWithoutYearMonth(
        string $table,
        string $column,
        string $typeColName,
        string $typeValue = null,
        string $prefix = '',
        int $digits = 4,
        int $size = 13,
        string $splitter = '-',
        string $suffixSeparator = '',
    ): string;

    public function branchCode(?int $parentBranchId = null);
    public function categoryCode(int $type): ?string;
    public function brandCode(): ?string;
    public function warrantyCode(): ?string;
}
