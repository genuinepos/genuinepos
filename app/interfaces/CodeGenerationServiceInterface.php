<?php

namespace App\Interface;

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
        ?string $connection = 'mysql',
    ): string;

    public function generateMonthWise(
        string $table,
        string $column = 'code',
        string $prefix = '',
        int $digits = 4,
        int $size = 13,
        string $splitter = '-',
        string $suffixSeparator = '',
        ?string $connection = 'mysql',
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
        ?string $connection = 'mysql'
    ): string;
}
