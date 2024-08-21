<?php

namespace Modules\SAAS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SAAS\Entities\Plan;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = array(
            array('id' => 1,'plan_type' => '1', 'name' => 'Trial', 'slug' => 'trial', 'description' => '<p>Get familiar with your <strong>7 Days</strong> Trial. Manage your stores and start your sales in minutes. You can switch to regular plans when you determine.</p>', 'price_per_month' => '0', 'price_per_year' => '0.00', 'has_lifetime_period' => '0', 'lifetime_price' => '0.00', 'business_price_per_month' => '0.00', 'business_price_per_year' => '0.00', 'business_lifetime_price' => '0.00', 'is_trial_plan' => '1', 'trial_days' => '14', 'trial_shop_count' => '2', 'applicable_lifetime_years' => '12', 'status' => '1', 'features' => '{"hrm": 1, "sales": 1, "setup": 1, "users": 1, "contacts": 1, "purchase": 1, "ecommerce": 1, "inventory": 1, "accounting": 1, "user_count": "10", "communication": 1, "manufacturing": "1", "employee_count": "30", "task_management": 1, "transfer_stocks": 1, "warehouse_count": "3", "stock_adjustments": 1, "cash_counter_count": "3"}', 'deleted_at' => NULL, 'created_at' => '2023-10-15 16:06:52', 'updated_at' => '2024-03-05 19:15:40'),
            array('id' => 2, 'plan_type' => '1', 'name' => 'BASIC', 'slug' => 'basic', 'description' => '<p>Our monthly silver package powers businesses in affordable price. With all the features come with it, managing your business will feel like a breeze.&nbsp;</p>', 'price_per_month' => '9', 'price_per_year' => '60.00', 'has_lifetime_period' => '1', 'lifetime_price' => '620.00', 'business_price_per_month' => '9.00', 'business_price_per_year' => '60.00', 'business_lifetime_price' => '620.00', 'is_trial_plan' => '0', 'trial_days' => '0', 'trial_shop_count' => NULL, 'applicable_lifetime_years' => '12', 'status' => '1', 'features' => '{"hrm": 1, "sales": 1, "setup": 1, "users": 1, "contacts": 1, "purchase": 1, "services": "1", "ecommerce": 1, "inventory": 1, "accounting": 1, "user_count": "3", "communication": 1, "manufacturing": "1", "employee_count": "7", "task_management": 1, "transfer_stocks": 1, "warehouse_count": "1", "stock_adjustments": 1, "cash_counter_count": "3"}', 'deleted_at' => NULL, 'created_at' => '2023-10-15 16:06:52', 'updated_at' => '2024-06-12 19:54:23'),
            array('id' => 3, 'plan_type' => '1', 'name' => 'PRO', 'slug' => 'Pro', 'description' => '<p>Gold package boost your business workflow with ease and make it semi-automated. You get 24/7 support with this package.</p>', 'price_per_month' => '15', 'price_per_year' => '108.00', 'has_lifetime_period' => '1', 'lifetime_price' => '1200.00', 'business_price_per_month' => '12.00', 'business_price_per_year' => '108.00', 'business_lifetime_price' => '1200.00', 'is_trial_plan' => '0', 'trial_days' => '0', 'trial_shop_count' => NULL, 'applicable_lifetime_years' => '12', 'status' => '1', 'features' => '{"hrm": 1, "sales": 1, "setup": 1, "users": 1, "contacts": 1, "purchase": 1, "services": "1", "ecommerce": 1, "inventory": 1, "accounting": 1, "user_count": "5", "communication": 1, "manufacturing": "1", "employee_count": "10", "task_management": 1, "transfer_stocks": 1, "warehouse_count": "3", "stock_adjustments": 1, "cash_counter_count": "5"}', 'deleted_at' => NULL, 'created_at' => '2023-10-15 16:06:52', 'updated_at' => '2024-06-12 19:54:31'),
            array('id' => 4, 'plan_type' => '1', 'name' => 'BUSINESS', 'slug' => 'BUSINESS', 'description' => NULL, 'price_per_month' => '20', 'price_per_year' => '180.00', 'has_lifetime_period' => '1', 'lifetime_price' => '1500.00', 'business_price_per_month' => '20.00', 'business_price_per_year' => '180.00', 'business_lifetime_price' => '1500.00', 'is_trial_plan' => '0', 'trial_days' => '0', 'trial_shop_count' => NULL, 'applicable_lifetime_years' => '12', 'status' => '1', 'features' => '{"hrm": 1, "sales": 1, "setup": 1, "users": 1, "contacts": 1, "purchase": 1, "services": "1", "ecommerce": 1, "inventory": 1, "accounting": 1, "user_count": "90", "communication": 1, "manufacturing": "1", "employee_count": "60", "task_management": 1, "transfer_stocks": 1, "warehouse_count": "3", "stock_adjustments": 1, "cash_counter_count": "5"}', 'deleted_at' => NULL, 'created_at' => '2023-11-24 00:08:53', 'updated_at' => '2024-06-12 19:54:40'),
            array('id' => 5, 'plan_type' => '1', 'name' => 'slim', 'slug' => 'slim', 'description' => NULL, 'price_per_month' => '7', 'price_per_year' => '48.00', 'has_lifetime_period' => '1', 'lifetime_price' => '500.00', 'business_price_per_month' => '7.00', 'business_price_per_year' => '48.00', 'business_lifetime_price' => '500.00', 'is_trial_plan' => '0', 'trial_days' => '0', 'trial_shop_count' => NULL, 'applicable_lifetime_years' => '12', 'status' => '0', 'features' => '{"hrm": 1, "sales": 1, "setup": 1, "users": 1, "contacts": 1, "purchase": 1, "services": "1", "ecommerce": 1, "inventory": 1, "accounting": 1, "user_count": "1", "communication": 1, "manufacturing": "1", "employee_count": "0", "task_management": 1, "transfer_stocks": 1, "warehouse_count": "0", "stock_adjustments": 1, "cash_counter_count": "1"}', 'deleted_at' => NULL, 'created_at' => '2024-02-05 20:48:12', 'updated_at' => '2024-06-12 19:54:49'),
            array('id' => 6, 'plan_type' => '2', 'name' => 'Enterprise Lite Solution', 'slug' => 'enterprise-lite-solution', 'description' => NULL, 'price_per_month' => '9', 'price_per_year' => '60.00', 'has_lifetime_period' => '1', 'lifetime_price' => '80.00', 'business_price_per_month' => '9.00', 'business_price_per_year' => '60.00', 'business_lifetime_price' => '500.00', 'is_trial_plan' => '0', 'trial_days' => '0', 'trial_shop_count' => NULL, 'applicable_lifetime_years' => '550', 'status' => '1', 'features' => '{"hrm": 1, "sales": 1, "setup": 1, "users": 1, "contacts": 1, "purchase": 1, "services": "1", "ecommerce": 1, "inventory": 1, "accounting": 1, "user_count": "3", "communication": 1, "manufacturing": "1", "employee_count": "10", "task_management": 1, "transfer_stocks": 1, "warehouse_count": "2", "stock_adjustments": 1, "cash_counter_count": "3"}', 'deleted_at' => NULL, 'created_at' => '2024-02-19 14:57:05', 'updated_at' => '2024-06-12 19:54:58')
        );

        foreach ($plans as $plan) {

            $exists =  \Illuminate\Support\Facades\DB::table('plans')->where('id', $plan['id'])->first();
            if (!isset($exists)) {

                Plan::insert($plan);
            }
        }
    }
}
