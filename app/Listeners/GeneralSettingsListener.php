<?php

namespace App\Listeners;

use Exception;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneralSettingsListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(Authenticated $event)
    {
        try {
            // $generalSettings = GeneralSetting::where('branch_id', $event?->user?->branch_id)
            //     ->orWhereIn('key', [
            //         'business_or_shop__business_name',
            //         'business_or_shop__business_logo',
            //         'business_or_shop__address',
            //         'business_or_shop__email',
            //         'business_or_shop__phone',
            //     ])->pluck('value', 'key')->toArray();

            $branchId = $event?->user?->branch_id ? $event?->user?->branch_id : null;
            $cacheKey = "generalSettings_{$branchId}";

            $generalSettings = Cache::rememberForever($cacheKey, function () use ($branchId, $cacheKey) {

                return GeneralSetting::where('branch_id', $branchId)
                    ->orWhereIn('key', [
                        'subscription__has_business',
                        'subscription__branch_count',
                        'subscription__is_completed_business_setup',
                        'subscription__is_completed_branch_startup',
                        'business_or_shop__business_name',
                        'business_or_shop__business_logo',
                        'business_or_shop__address',
                        'business_or_shop__email',
                        'business_or_shop__phone',
                    ])->pluck('value', 'key')->toArray();
            });

            $cacheKey = "baseCurrency";
            $baseCurrency = Cache::rememberForever($cacheKey, function () use ($cacheKey) {

                return DB::table('general_settings')
                    ->where('branch_id', null)->where('key', 'business_or_shop__currency_id')
                    ->leftJoin('currencies', 'general_settings.value', 'currencies.id')
                    ->select('currencies.id', 'currencies.country', 'currencies.currency', 'currencies.code', 'currencies.symbol', 'currencies.currency_rate')->first();
            });

            $generalSettings['base_currency_country'] = $baseCurrency->country;
            $generalSettings['base_currency_name'] = $baseCurrency->currency;
            $generalSettings['base_currency_code'] = $baseCurrency->code;
            $generalSettings['base_currency_symbol'] = $baseCurrency->symbol;

            if (!isset($branchId)) {

                session()->put('base_currency_symbol', $generalSettings['business_or_shop__currency_symbol']);
            }

            $branch = $event?->user?->branch;
            if (isset($branch) && isset($branch->parent_branch_id)) {

                $prefixes = [
                    'business_or_shop__',
                    'reward_point_settings__',
                    'send_email__',
                    'send_sms__',
                    'service_settings__',
                    'service_settings_pdf_label__',
                ];

                $cacheKey = "parentBranchGeneralSettings_{$branch->parent_branch_id}";

                $parentBranchGeneralSettings = Cache::rememberForever($cacheKey, function () use ($branch, $prefixes) {

                    $query = GeneralSetting::query()->where('branch_id', $branch->parent_branch_id)
                        ->whereNotIn(
                            'key',
                            [
                                'business_or_shop__business_name',
                                'business_or_shop__currency_id',
                                'business_or_shop__currency_symbol',
                                'business_or_shop__date_format',
                                'business_or_shop__time_format',
                                'business_or_shop__timezone',
                            ]
                        );

                    $query->where(function ($query) use ($prefixes) {

                        foreach ($prefixes as $prefix) {

                            $query->orWhere('key', 'LIKE', $prefix . '%');
                        }
                    });

                    $parentBranchGeneralSettings = $query->get();

                    return $parentBranchGeneralSettings;
                });

                // $query = GeneralSetting::query()->where('branch_id', $branch->parent_branch_id)
                //     ->whereNotIn(
                //         'key',
                //         [
                //             'business_or_shop__business_name',
                //             'business_or_shop__currency_id',
                //             'business_or_shop__currency_symbol',
                //             'business_or_shop__date_format',
                //             'business_or_shop__time_format',
                //             'business_or_shop__timezone',
                //         ]
                //     );

                // $query->where(function ($query) use ($prefixes) {
                //     foreach ($prefixes as $prefix) {
                //         $query->orWhere('key', 'LIKE', $prefix . '%');
                //     }
                // });

                // $parentBranchGeneralSettings = $query->get();

                foreach ($parentBranchGeneralSettings as $parentBranchGeneralSetting) {
                    $generalSettings[$parentBranchGeneralSetting->key] = $parentBranchGeneralSetting->value;
                }
            }

            $financialYearStartMonth = $generalSettings['business_or_shop__financial_year_start_month'];

            $dateFormat = $generalSettings['business_or_shop__date_format'];
            $__financialYearStartMonth = date("m", mktime(0, 0, 0, $financialYearStartMonth, 1, date("Y")));

            $startDateFormat = 'Y' . '-' . $__financialYearStartMonth . '-' . '1';
            $startDate = date($startDateFormat);
            $endDate = date('Y-m-d', strtotime(' + 1 year - 1 day', strtotime($startDate)));
            $financialYear = date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));
            $generalSettings['business_or_shop__financial_year'] = $financialYear;
            $generalSettings['business_or_shop__financial_year_start_date'] = date($dateFormat, strtotime($startDate));
            $generalSettings['business_or_shop__financial_year_end_date'] = date($dateFormat, strtotime($endDate));

            if (isset($generalSettings['invoice_layout__add_sale_invoice_layout_id'])) {

                $columns = Cache::rememberForever('invoice_layouts_table_columns', function () {

                    return Schema::getColumnListing('invoice_layouts');
                });

                $excludedColumns = ['created_at', 'updated_at'];
                $selectedColumns = array_diff($columns, $excludedColumns);

                // $invoiceAddSaleLayout = DB::table('invoice_layouts')
                //     ->where('id', $generalSettings['invoice_layout__add_sale_invoice_layout_id'])
                //     ->select($selectedColumns)
                //     ->first();

                $cacheKey = "invoiceAddSaleLayout_{$generalSettings['invoice_layout__add_sale_invoice_layout_id']}";

                $invoiceAddSaleLayout = Cache::rememberForever($cacheKey, function () use ($generalSettings, $selectedColumns) {

                    return DB::table('invoice_layouts')
                        ->where('id', $generalSettings['invoice_layout__add_sale_invoice_layout_id'])
                        ->select($selectedColumns)
                        ->first();
                });

                if (isset($invoiceAddSaleLayout)) {

                    $generalSettings['add_sale_invoice_layout'] = $invoiceAddSaleLayout;
                }

                // $invoicePosSaleLayout = DB::table('invoice_layouts')
                //     ->where('id', $generalSettings['invoice_layout__pos_sale_invoice_layout_id'])
                //     ->select($selectedColumns)
                //     ->first();

                $cacheKey = "invoicePosSaleLayout_{$generalSettings['invoice_layout__pos_sale_invoice_layout_id']}";

                $invoicePosSaleLayout = Cache::rememberForever($cacheKey, function () use ($generalSettings, $selectedColumns) {

                    return DB::table('invoice_layouts')
                        ->where('id', $generalSettings['invoice_layout__pos_sale_invoice_layout_id'])
                        ->select($selectedColumns)
                        ->first();
                });

                if (isset($invoicePosSaleLayout)) {

                    $generalSettings['pos_sale_invoice_layout'] = $invoicePosSaleLayout;
                }
            }

            config([
                'generalSettings' => $generalSettings,
                // 'mail.mailers.smtp.transport' => $generalSettings['email_config__MAIL_MAILER'] ?? config('mail.mailers.smtp.transport'),
                // 'mail.mailers.smtp.host' => $generalSettings['email_config__MAIL_HOST'] ?? config('mail.mailers.smtp.host'),
                // 'mail.mailers.smtp.port' => $generalSettings['email_config__MAIL_PORT'] ?? config('mail.mailers.smtp.port'),
                // 'mail.mailers.smtp.encryption' => $generalSettings['email_config__MAIL_ENCRYPTION'] ?? config('mail.mailers.smtp.encryption'),
                // 'mail.mailers.smtp.username' => $generalSettings['email_config__MAIL_USERNAME'] ?? config('mail.mailers.smtp.username'),
                // 'mail.mailers.smtp.password' => $generalSettings['email_config__MAIL_PASSWORD'] ?? config('mail.mailers.smtp.password'),
                // 'mail.mailers.smtp.timeout' => $generalSettings['email_config__MAIL_TIMEOUT'] ?? config('mail.mailers.smtp.timeout'),
                // 'mail.mailers.smtp.auth_mode' => $generalSettings['email_config__MAIL_AUTH_MODE'] ?? config('mail.mailers.smtp.auth_mode'),
            ]);

            $dateFormat = $generalSettings['business_or_shop__date_format'];
            $__date_format = str_replace('-', '/', $dateFormat);

            if (isset($generalSettings)) {
                view()->share('generalSettings', $generalSettings);
                view()->share('__date_format', $__date_format);
            }
            // }
        } catch (Exception $e) {
        }
    }
}
