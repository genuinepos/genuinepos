<?php

namespace App\Listeners;

use Exception;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Queue\InteractsWithQueue;
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

            if (Schema::hasTable('general_settings') && GeneralSetting::count() > 0) {

                $generalSettings = Cache::get('generalSettings');

                if (!isset($generalSettings)) {

                    $generalSettings = GeneralSetting::where('branch_id', $event?->user?->branch_id)
                        ->orWhereIn('key', [
                            'addons__hrm',
                            'addons__manage_task',
                            'addons__service',
                            'addons__manufacturing',
                            'addons__e_commerce',
                            'addons__branch_limit',
                            'addons__cash_counter_limit',
                            'business_or_shop__business_name'
                        ])->pluck('value', 'key')->toArray();

                    $branch = $event?->user?->branch;
                    if (isset($branch) && isset($branch->parent_branch_id)) {

                        $prefixes = [
                            'business_or_shop__',
                            'reward_point_settings__',
                            'send_email__',
                            'send_sms__',
                        ];

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
                    Cache::rememberForever('generalSettings', function () use ($generalSettings) {
                        return $generalSettings;
                    });
                }

                // request()->merge(['generalSettings' => $generalSettings]);
                if (isset($generalSettings['invoice_layout__add_sale_invoice_layout_id'])) {

                    $columns = Schema::getColumnListing('invoice_layouts');
                    $excludedColumns = ['created_at', 'updated_at'];
                    $selectedColumns = array_diff($columns, $excludedColumns);
                    $invoiceAddSaleLayout = DB::table('invoice_layouts')
                        ->where('id', $generalSettings['invoice_layout__add_sale_invoice_layout_id'])
                        ->select($selectedColumns)
                        ->first();
                    if (isset($invoiceAddSaleLayout)) {
                        $generalSettings['add_sale_invoice_layout'] = $invoiceAddSaleLayout;
                    }
                    $invoicePosSaleLayout = DB::table('invoice_layouts')
                        ->where('id', $generalSettings['invoice_layout__pos_sale_invoice_layout_id'])
                        ->select($selectedColumns)
                        ->first();
                    if (isset($invoicePosSaleLayout)) {
                        $generalSettings['pos_sale_invoice_layout'] = $invoicePosSaleLayout;
                    }
                }

                $subscription = DB::table('subscriptions')
                    ->leftJoin('pos.plans', 'subscriptions.plan_id', 'pos.plans.id')
                    ->select(
                        [
                            'subscriptions.initial_due_amount',
                            'subscriptions.initial_payment_status',
                            'subscriptions.initial_plan_start_date',
                            'subscriptions.initial_plan_expire_date',
                            'pos.plans.is_trial_plan',
                            'pos.plans.trial_days',
                        ]
                    )->first();

                $generalSettings['subscription'] = $subscription;

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
            }
        } catch (Exception $e) {
        }
    }
}
