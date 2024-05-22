<?php

namespace App\Listeners;

use Exception;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
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
        // $branchId = auth()?->user()?->branch_id ? auth()->user()->branch_id : null;
        //     $tenantId = tenant('id');
        //     $generalSettingsCacheKey = "{$tenantId}_generalSettings_{$branchId}";

        //     $generalSettings = Cache::get($generalSettingsCacheKey);
        // $generalSettings = Cache::get('generalSettings');
        // dd($generalSettings);

        try {

            // if (Schema::hasTable('general_settings') && GeneralSetting::count() > 0) {
            // if (Schema::hasTable('general_settings') && GeneralSetting::count() > 0) {

            // if (!isset($generalSettings)) {

            // $generalSettings = GeneralSetting::where('branch_id', $event?->user?->branch_id)
            //     ->orWhereIn('key', [
            //         'business_or_shop__business_name',
            //         'business_or_shop__business_logo',
            //         'business_or_shop__address',
            //         'business_or_shop__email',
            //         'business_or_shop__phone',
            //     ])->pluck('value', 'key')->toArray();

            $branchId = $event?->user?->branch_id ? $event?->user?->branch_id : null;
            $tenantId = tenant('id');  // Ensure this retrieves the current tenant's ID
            $cacheKey = "{$tenantId}_generalSettings_{$branchId}";

            $generalSettings = Cache::rememberForever($cacheKey, function () use ($branchId, $cacheKey) {

                $settings = GeneralSetting::where('branch_id', $branchId)
                    ->orWhereIn('key', [
                        'business_or_shop__business_name',
                        'business_or_shop__business_logo',
                        'business_or_shop__address',
                        'business_or_shop__email',
                        'business_or_shop__phone',
                    ])->pluck('value', 'key')->toArray();

                return $settings;
            });

            $branch = $event?->user?->branch;
            if (isset($branch) && isset($branch->parent_branch_id)) {

                $prefixes = [
                    'business_or_shop__',
                    'reward_point_settings__',
                    'send_email__',
                    'send_sms__',
                ];

                $cacheKey = "{$tenantId}_parentBranchGeneralSettings_{$branch->parent_branch_id}";

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

            // Cache::rememberForever('generalSettings', function () use ($generalSettings) {
            //     return $generalSettings;
            // });

            // }

            // request()->merge(['generalSettings' => $generalSettings]);
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

                $cacheKey = "{$tenantId}_invoiceAddSaleLayout_{$generalSettings['invoice_layout__add_sale_invoice_layout_id']}";

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

                $cacheKey = "{$tenantId}_invoicePosSaleLayout_{$generalSettings['invoice_layout__pos_sale_invoice_layout_id']}";

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

            $cacheKey = "{$tenantId}_GeneralSettings_subscription";

            $subscription = Cache::rememberForever($cacheKey, function () {

                return DB::table('subscriptions')
                    ->leftJoin('pos.plans', 'subscriptions.plan_id', 'pos.plans.id')
                    ->select(
                        [
                            'subscriptions.id',
                            'subscriptions.plan_id',
                            'subscriptions.has_due_amount',
                            'subscriptions.initial_plan_start_date',
                            'subscriptions.due_repayment_date',
                            'subscriptions.has_business',
                            'subscriptions.is_completed_business_startup',
                            'subscriptions.is_completed_branch_startup',
                            'subscriptions.business_expire_date',
                            'pos.plans.plan_type',
                            'pos.plans.name as plan_name',
                            'pos.plans.is_trial_plan',
                            'pos.plans.trial_days',
                            'pos.plans.features',
                            'subscriptions.current_shop_count',
                            'subscriptions.trial_start_date',
                            'subscriptions.has_business',
                            'subscriptions.business_expire_date',
                        ]
                    )->first();
            });

            // $subscription = DB::table('subscriptions')
            //     ->leftJoin('pos.plans', 'subscriptions.plan_id', 'pos.plans.id')
            //     ->select(
            //         [
            //             'subscriptions.id',
            //             'subscriptions.plan_id',
            //             'subscriptions.has_due_amount',
            //             'subscriptions.initial_plan_start_date',
            //             'subscriptions.due_repayment_date',
            //             'subscriptions.has_business',
            //             'subscriptions.is_completed_business_startup',
            //             'subscriptions.is_completed_branch_startup',
            //             'subscriptions.business_expire_date',
            //             'pos.plans.plan_type',
            //             'pos.plans.name as plan_name',
            //             'pos.plans.is_trial_plan',
            //             'pos.plans.trial_days',
            //             'pos.plans.features',
            //             'subscriptions.current_shop_count',
            //             'subscriptions.trial_start_date',
            //             'subscriptions.has_business',
            //             'subscriptions.business_expire_date',
            //         ]
            //     )->first();

            $generalSettings['subscription'] = $subscription;
            $generalSettings['subscription']->features = json_decode($generalSettings['subscription']->features, true);

            config([
                'generalSettings' => $generalSettings,
                // Tenant separated email config start
                // 'mail.mailers.smtp.transport' => $generalSettings['email_config__MAIL_MAILER'] ?? config('mail.mailers.smtp.transport'),
                // 'mail.mailers.smtp.host' => $generalSettings['email_config__MAIL_HOST'] ?? config('mail.mailers.smtp.host'),
                // 'mail.mailers.smtp.port' => $generalSettings['email_config__MAIL_PORT'] ?? config('mail.mailers.smtp.port'),
                // 'mail.mailers.smtp.encryption' => $generalSettings['email_config__MAIL_ENCRYPTION'] ?? config('mail.mailers.smtp.encryption'),
                // 'mail.mailers.smtp.username' => $generalSettings['email_config__MAIL_USERNAME'] ?? config('mail.mailers.smtp.username'),
                // 'mail.mailers.smtp.password' => $generalSettings['email_config__MAIL_PASSWORD'] ?? config('mail.mailers.smtp.password'),
                // 'mail.mailers.smtp.timeout' => $generalSettings['email_config__MAIL_TIMEOUT'] ?? config('mail.mailers.smtp.timeout'),
                // 'mail.mailers.smtp.auth_mode' => $generalSettings['email_config__MAIL_AUTH_MODE'] ?? config('mail.mailers.smtp.auth_mode'),
                // Tenant separated email config ends
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
