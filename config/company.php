<?php

return [
    'print_on_sale' => (bool) env('PRINT_SD_SALE'),
    'print_on_payment' => (bool) env('PRINT_SD_PAYMENT'),
    'print_on_purchase' => (bool) env('PRINT_SD_PURCHASE'),
    'print_on_others' => (bool) env('PRINT_SD_OTHERS'),
    'print_on_company' => (bool) env('PRINT_SD_COMPANY_NAME'),
];
