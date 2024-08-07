<?php

namespace Modules\SAAS\Services;

use App\Enums\BooleanType;
use Illuminate\Support\Str;
use Modules\SAAS\Entities\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Modules\SAAS\Interfaces\PlanServiceInterface;

class PlanService implements PlanServiceInterface
{
    public function storePlan(object $request): ?array
    {
        $checkFeatures = $request->features;
        unset($checkFeatures['user_count']);
        unset($checkFeatures['employee_count']);
        unset($checkFeatures['cash_counter_count']);
        unset($checkFeatures['warehouse_count']);

        if (count($checkFeatures) == 0) {

            return ['pass' => false, 'msg' => 'Plan features are not selected.'];
        }

        $preparedPlanFeatures = $this->preparedPlanFeatures(request: $request);

        $plan = Plan::create([
            'plan_type' => $request->plan_type,
            'name' => $request->name,
            'slug' => $request->slug ? $request->slug : Str::slug($request->name),
            'price_per_month' => $request->price_per_month,
            'price_per_year' => $request->price_per_year,
            'has_lifetime_period' => $request->has_lifetime_period,
            'lifetime_price' => $request->has_lifetime_period == BooleanType::True->value ? $request->lifetime_price : BooleanType::False->value,
            'applicable_lifetime_years' => $request->has_lifetime_period == BooleanType::True->value ? $request->applicable_lifetime_years : BooleanType::False->value,
            'business_price_per_month' => $request->business_price_per_month,
            'business_price_per_year' => $request->business_price_per_year,
            'business_lifetime_price' => $request->has_lifetime_period == BooleanType::True->value ? $request->business_lifetime_price : BooleanType::False->value,
            'description' => $request->description,
            'features' => $preparedPlanFeatures,
            'status' => $request->status,
        ]);

        return null;
    }

    public function updatePlan(int $id, object $request): ?array
    {
        $countExcept = ['user_count', 'employee_count', 'cash_counter_count', 'warehouse_count'];
        $checkFeatures = $request->features;
        unset($checkFeatures['user_count']);
        unset($checkFeatures['employee_count']);
        unset($checkFeatures['cash_counter_count']);
        unset($checkFeatures['warehouse_count']);

        if (count($checkFeatures) == 0) {

            return ['pass' => false, 'msg' => 'Plan features are not selected.'];
        }

        $updatePlan = $this->singlePlanById(id: $id);

        $updatePlan->name = $request->name;
        $updatePlan->slug = $request->slug ? $request->slug : Str::slug($request->name);

        if ($updatePlan->is_trial_plan == BooleanType::False->value) {

            $updatePlan->plan_type = $request->plan_type;
            $updatePlan->price_per_month = $request->price_per_month;
            $updatePlan->has_lifetime_period = $request->has_lifetime_period;
            $updatePlan->price_per_year = $request->price_per_year;
            $updatePlan->lifetime_price = $request->has_lifetime_period == BooleanType::True->value ? $request->lifetime_price : BooleanType::False->value;
            $updatePlan->applicable_lifetime_years = $request->has_lifetime_period == BooleanType::True->value ? $request->applicable_lifetime_years : BooleanType::False->value;
            $updatePlan->business_price_per_month = $request->business_price_per_month;
            $updatePlan->business_price_per_year = $request->business_price_per_year;
            $updatePlan->business_lifetime_price = $request->has_lifetime_period == BooleanType::True->value ? $request->business_lifetime_price : BooleanType::False->value;
        } elseif ($updatePlan->is_trial_plan == BooleanType::True->value) {

            $updatePlan->trial_days = $request->trial_days;
            $updatePlan->trial_shop_count = $request->trial_shop_count;
        }

        $updatePlan->features = $this->preparedPlanFeatures(request: $request);
        $updatePlan->description = $request->description;
        $updatePlan->status = $request->status;
        $updatePlan->save();

        $this->clearTenantPlanCache(planId: $updatePlan->id);

        return null;
    }

    public function deletePlan(int $id): array
    {
        $deletePlan = $this->singlePlanById(id: $id, with: ['userSubscriptions']);

        if (isset($deletePlan)) {

            if ($deletePlan->is_trial_plan == BooleanType::True->value) {

                return ['pass' => false, 'msg' => 'Trial Plan cant be deleted.'];
            }

            if (count($deletePlan->userSubscriptions) > 0) {

                return ['pass' => false, 'msg' => 'Plan cant be deleted. This plan is belonging to one or many subscriptions.'];
            }

            $deletePlan->delete();
        }

        return ['pass' => true];
    }

    public function plans(array $with = null): object
    {
        $query = Plan::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query;
    }

    public function singlePlanById(int $id, array $with = null): ?object
    {
        $query = Plan::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('id', $id)->first();
    }

    public function trialPlan(array $with = null): ?object
    {
        $query = Plan::query();

        if (isset($with)) {

            $query->with($with);
        }

        return $query->where('is_trial_plan', BooleanType::True->value)->firstOrFail();
    }

    private function clearTenantPlanCache(int $planId): void
    {
        Artisan::call('cache:clear');
        // $userSubscriptions = DB::table('user_subscriptions')
        //     ->where('plan_id', $planId)
        //     ->leftJoin('users', 'user_subscriptions.user_id', 'users.id')
        //     ->select('users.tenant_id')
        //     ->get();

        // foreach ($userSubscriptions as $userSubscription) {

        //     if ($userSubscription->tenant_id) {

        //         Artisan::call('tenants:run cache:clear --tenants=' . $userSubscription->tenant_id);
        //     }
        // }

        // // // Get the default database connection name
        // // $defaultConnection = DB::getDefaultConnection();

        // // // Fetch user subscriptions with tenant IDs
        // // $userSubscriptions = DB::table('user_subscriptions')
        // //     ->where('plan_id', $planId)
        // //     ->leftJoin('users', 'user_subscriptions.user_id', 'users.id')
        // //     ->select('users.tenant_id')
        // //     ->get();

        // // foreach ($userSubscriptions as $userSubscription) {

        // //     if ($userSubscription->tenant_id) {
        // //         // Switch to the tenant's database
        // //         $tenantDatabase = 'pos_' . $userSubscription->tenant_id;
        // //         DB::statement('use ' . $tenantDatabase);

        // //         $cacheKey = $userSubscription->tenant_id . "_GeneralSettings_subscription";

        // //         // Log the cache key for debugging
        // //         Log::info("Checking cache key: " . $cacheKey);

        // //         // Verify the cache key exists
        // //         if (Cache::store('redis')->has($cacheKey)) { // Change 'redis' to your cache store if different
        // //             Log::info("Cache key exists: " . $cacheKey);

        // //             // Forget cache for the tenant
        // //             if (Cache::store('redis')->forget($cacheKey)) { // Change 'redis' to your cache store if different
        // //                 Log::info("Cache cleared for key: " . $cacheKey);
        // //             } else {
        // //                 Log::warning("Failed to clear cache for key: " . $cacheKey);
        // //             }
        // //         } else {
        // //             Log::warning("Cache key does not exist: " . $cacheKey);
        // //         }

        // //         // Reconnect to the default database
        // //         DB::setDefaultConnection($defaultConnection);
        // //         DB::reconnect($defaultConnection);
        // //     }
        // // }
    }

    private function preparedPlanFeatures(object $request): array
    {
        return [
            'contacts' => isset($request->features['contacts']) ? 1 : 0,
            'inventory' => isset($request->features['inventory']) ? 1 : 0,
            'purchase' => isset($request->features['purchase']) ? 1 : 0,
            'sales' => isset($request->features['sales']) ? 1 : 0,
            'transfer_stocks' => isset($request->features['transfer_stocks']) ? 1 : 0,
            'stock_adjustments' => isset($request->features['stock_adjustments']) ? 1 : 0,
            'accounting' => isset($request->features['accounting']) ? 1 : 0,
            'users' => isset($request->features['users']) ? 1 : 0,
            'user_count' => isset($request->features['user_count']) ? $request->features['user_count'] : 0,
            'hrm' => isset($request->features['hrm']) ? 1 : 0,
            'employee_count' => isset($request->features['employee_count']) ? $request->features['employee_count'] : 0,
            'manufacturing' => isset($request->features['manufacturing']) ? $request->features['manufacturing'] : 0,
            'services' => isset($request->features['services']) ? $request->features['services'] : 0,
            'task_management' => isset($request->features['task_management']) ? 1 : 0,
            'communication' => isset($request->features['communication']) ? 1 : 0,
            'setup' => isset($request->features['setup']) ? 1 : 0,
            'cash_counter_count' => isset($request->features['cash_counter_count']) ? $request->features['cash_counter_count'] : 0,
            'warehouse_count' => isset($request->features['warehouse_count']) ? $request->features['warehouse_count'] : 0,
            'ecommerce' => isset($request->features['ecommerce']) ? 1 : 0,
            'advertisements' => isset($request->features['advertisements']) ? 1 : 0,
        ];
    }
}
