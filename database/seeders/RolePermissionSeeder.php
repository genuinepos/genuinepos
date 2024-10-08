<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Log::info('RolePermissionSeeder Start');
        // echo 'Start'.PHP_EOL;
        // Artisan::call('optimize:clear');
        Artisan::call('permission:cache-reset');
        $this->truncateRolePermissionDataButKeepOldData();
        $this->createRolePermission();
        $this->syncRolesPermissions();
        // echo 'END'.PHP_EOL;
        // Log::info('RolePermissionSeeder End');
    }

    public function truncateRolePermissionDataButKeepOldData(): void
    {
        // echo 'Truncate Start'.PHP_EOL;
        Schema::disableForeignKeyConstraints();

        if (Role::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `roles` AUTO_INCREMENT = 1');
        }

        Permission::truncate();
        if (Permission::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `permissions` AUTO_INCREMENT = 1');
        }

        Schema::enableForeignKeyConstraints();
        // echo 'Truncate Completed'.PHP_EOL;
    }

    public function createRolePermission(): void
    {
        // echo 'Role creation started.' . PHP_EOL;
        $roles = $this->getRolesArray();
        foreach ($roles as $role) {

            $roleAlreadyExists = Role::where('id', $role['id'])->exists();
            if (!$roleAlreadyExists) {

                Role::insert(['id' => $role['id'], 'name' => $role['name'], 'guard_name' => 'web']);
            }
        }

        $permissions = $this->getPermissionsArray();
        foreach ($permissions as $permission) {

            // $permissionExists = Permission::where('id', $permission['id'])->where('name', $permission['name'])->exists();

            // if (!$permissionExists) {

            //     Permission::create([
            //         'id' => $permission['id'],
            //         'name' => $permission['name'],
            //         // 'guard_name' => 'web',
            //     ]);

            //     // echo 'Created: ' . $permission['name'] . PHP_EOL;
            // }

            Permission::insert([
                'id' => $permission['id'],
                'name' => $permission['name'],
                'guard_name' => 'web',
            ]);
        }
    }

    public function syncRolesPermissions(): void
    {
        // echo 'Sync permission' . PHP_EOL;
        $roles = $this->getRolesArray();
        foreach ($roles as $role) {

            $role = Role::where('id', $role['id'])->first();

            if (isset($role)) {

                // echo 'Role sync to ' . $role->name . PHP_EOL;

                $hasAccessToAllAreaPermission = $role->hasPermissionTo('has_access_to_all_area');
                $hasViewOnlyOonTransactionsPermission = $role->hasPermissionTo('view_only_won_transactions');
                // $hasServiceInvoicesOnlyOwnPermission = $role->hasPermissionTo('service_invoices_only_own');
                // $hasServiceQuotationsOnlyOwnPermission = $role->hasPermissionTo('service_quotations_only_own');
                // $hasSaleQuotationsOnlyOwnPermission = $role->hasPermissionTo('sale_quotations_only_own');
                // $hasSaleDraftsOnlyOwnPermission = $role->hasPermissionTo('sale_drafts_only_own');
                // $hasSalesOrdersOnlyOwnPermission = $role->hasPermissionTo('sales_orders_only_own');
                // $hasSalesReturnOnlyOwnPermission = $role->hasPermissionTo('sales_return_only_own');
                $hasShopIndexPermission = $role->hasPermissionTo('branches_index');
                $hasShopCreatePermission = $role->hasPermissionTo('branches_create');
                $hasShopEditPermission = $role->hasPermissionTo('branches_edit');
                $hasShopDeletePermission = $role->hasPermissionTo('branches_delete');
                $hasBillingIndexPermission = $role->hasPermissionTo('billing_index');
                $hasBillingUpgradePlanPermission = $role->hasPermissionTo('billing_upgrade_plan');
                $hasBillingShopAddPermission = $role->hasPermissionTo('billing_branch_add');
                $hasBillingRenewShopPermission = $role->hasPermissionTo('billing_renew_branch');
                $hasBillingBusinessAddPermission = $role->hasPermissionTo('billing_business_add');
                $hasBillingPayDuePaymentPermission = $role->hasPermissionTo('billing_pay_due_payment');

                $permissions = $this->getPermissionsArray();
                $rolePermissions = $role->getPermissionNames();
                $countRolePermissions = count($rolePermissions);

                if ($role->id == 1 || $role->id == 2) {

                    $role->syncPermissions($permissions);
                    if ($role->id == 1) {

                        $role->revokePermissionTo('user_activities_log_only_own_log');
                        $role->revokePermissionTo('view_only_won_transactions');
                        // $role->revokePermissionTo('service_invoices_only_own');
                        // $role->revokePermissionTo('service_quotations_only_own');
                        // $role->revokePermissionTo('sale_quotations_only_own');
                        // $role->revokePermissionTo('sale_drafts_only_own');
                        // $role->revokePermissionTo('sales_orders_only_own');
                        // $role->revokePermissionTo('sales_return_only_own');
                    }

                    if ($role->id == 2) {

                        if (!$hasAccessToAllAreaPermission) {

                            $role->revokePermissionTo('has_access_to_all_area');
                        }

                        if (!$hasViewOnlyOonTransactionsPermission) {

                            $role->revokePermissionTo('view_only_won_transactions');
                        }

                        // if (!$hasServiceInvoicesOnlyOwnPermission) {

                        //     $role->revokePermissionTo('service_invoices_only_own');
                        // }

                        // if (!$hasSaleQuotationsOnlyOwnPermission) {

                        //     $role->revokePermissionTo('sale_quotations_only_own');
                        // }

                        // if (!$hasSaleDraftsOnlyOwnPermission) {

                        //     $role->revokePermissionTo('sale_drafts_only_own');
                        // }

                        // if (!$hasSalesOrdersOnlyOwnPermission) {

                        //     $role->revokePermissionTo('sales_orders_only_own');
                        // }

                        // if (!$hasServiceQuotationsOnlyOwnPermission) {

                        //     $role->revokePermissionTo('service_quotations_only_own');
                        // }

                        // if (!$hasSalesReturnOnlyOwnPermission) {

                        //     $role->revokePermissionTo('sales_return_only_own');
                        // }

                        // if (!$hasShopIndexPermission) {

                        //     $role->revokePermissionTo('branches_index');
                        // }

                        if (!$hasShopCreatePermission) {

                            $role->revokePermissionTo('branches_create');
                        }

                        if (!$hasShopEditPermission) {

                            $role->revokePermissionTo('branches_edit');
                        }

                        if (!$hasShopDeletePermission) {

                            $role->revokePermissionTo('branches_delete');
                        }

                        if (!$hasBillingIndexPermission) {

                            $role->revokePermissionTo('billing_index');
                        }

                        if (!$hasBillingUpgradePlanPermission) {

                            $role->revokePermissionTo('billing_upgrade_plan');
                        }

                        if (!$hasBillingShopAddPermission) {

                            $role->revokePermissionTo('billing_branch_add');
                        }

                        if (!$hasBillingRenewShopPermission) {

                            $role->revokePermissionTo('billing_renew_branch');
                        }

                        if (!$hasBillingBusinessAddPermission) {

                            $role->revokePermissionTo('billing_business_add');
                        }

                        if (!$hasBillingPayDuePaymentPermission) {

                            $role->revokePermissionTo('billing_pay_due_payment');
                        }
                    }
                } elseif (!$role->id == 1 && !$role->id == 2 && $countRolePermissions == 0) {

                    $role->syncPermissions($permissions);
                    $role->revokePermissionTo('has_access_to_all_area');
                    $role->revokePermissionTo('view_only_won_transactions');
                    // $role->revokePermissionTo('service_invoices_only_own');
                    // $role->revokePermissionTo('service_quotations_only_own');
                    // $role->revokePermissionTo('sale_quotations_only_own');
                    // $role->revokePermissionTo('sale_drafts_only_own');
                    // $role->revokePermissionTo('sales_orders_only_own');
                    // $role->revokePermissionTo('sales_return_only_own');
                    $role->revokePermissionTo('branches_index');
                    $role->revokePermissionTo('branches_create');
                    $role->revokePermissionTo('branches_edit');
                    $role->revokePermissionTo('branches_delete');

                    $role->revokePermissionTo('billing_index');
                    $role->revokePermissionTo('billing_upgrade_plan');
                    $role->revokePermissionTo('billing_branch_add');
                    $role->revokePermissionTo('billing_renew_branch');
                    $role->revokePermissionTo('billing_business_add');
                    $role->revokePermissionTo('billing_pay_due_payment');
                }

                // echo 'Role has been synced to ' . $role->name . ' successfully' . PHP_EOL;
            }
        }

        // $permissions = Permission::all();
        // $role = Role::first();
        // $role->syncPermissions($permissions);

        // $role = Role::skip(1)->first();
        // $role->syncPermissions($permissions);
    }

    public function getRolesArray(): array
    {
        $roles = [
            ['id' => '1', 'name' => 'superadmin'],
            ['id' => '2', 'name' => 'admin'],
            ['id' => '3', 'name' => 'accountant'],
            ['id' => '4', 'name' => 'sales'],
        ];

        return $roles;
    }

    public function getPermissionsArray(): array
    {
        $permissions = [
            ['id' => '1', 'name' => 'user_view'],
            ['id' => '2', 'name' => 'user_add'],
            ['id' => '3', 'name' => 'user_edit'],
            ['id' => '4', 'name' => 'user_delete'],
            ['id' => '5', 'name' => 'role_view'],
            ['id' => '6', 'name' => 'role_add'],
            ['id' => '7', 'name' => 'role_edit'],
            ['id' => '8', 'name' => 'role_delete'],
            ['id' => '9', 'name' => 'supplier_all'],
            ['id' => '10', 'name' => 'supplier_add'],
            ['id' => '11', 'name' => 'supplier_import'],
            ['id' => '12', 'name' => 'supplier_edit'],
            ['id' => '13', 'name' => 'supplier_delete'],
            ['id' => '14', 'name' => 'customer_all'],
            ['id' => '15', 'name' => 'customer_add'],
            ['id' => '16', 'name' => 'customer_import'],
            ['id' => '17', 'name' => 'customer_edit'],
            ['id' => '18', 'name' => 'customer_delete'],
            ['id' => '19', 'name' => 'customer_group'],
            ['id' => '20', 'name' => 'customer_report'],
            ['id' => '21', 'name' => 'supplier_report'],
            ['id' => '22', 'name' => 'product_all'],
            ['id' => '23', 'name' => 'product_add'],
            ['id' => '24', 'name' => 'product_edit'],
            ['id' => '25', 'name' => 'openingStock_add'],
            ['id' => '26', 'name' => 'product_delete'],
            ['id' => '33', 'name' => 'generate_barcode'],
            ['id' => '35', 'name' => 'stock_report'],
            ['id' => '36', 'name' => 'stock_in_out_report'],
            ['id' => '37', 'name' => 'purchase_all'],
            ['id' => '38', 'name' => 'purchase_add'],
            ['id' => '39', 'name' => 'purchase_edit'],
            ['id' => '40', 'name' => 'purchase_delete'],
            ['id' => '46', 'name' => 'purchase_sale_report'],
            ['id' => '47', 'name' => 'product_purchase_report'],
            ['id' => '48', 'name' => 'purchase_payment_report'],
            ['id' => '49', 'name' => 'stock_adjustment_all'],
            ['id' => '50', 'name' => 'stock_adjustment_add'],
            ['id' => '51', 'name' => 'stock_adjustment_delete'],
            ['id' => '53', 'name' => 'stock_adjustment_report'],
            // ['id' => '61', 'name' => 'pos_all'],
            // ['id' => '62', 'name' => 'pos_add'],
            // ['id' => '63', 'name' => 'pos_edit'],
            // ['id' => '64', 'name' => 'pos_delete'],
            // ['id' => '66', 'name' => 'create_add_sale'],
            // ['id' => '67', 'name' => 'view_add_sale'],
            // ['id' => '68', 'name' => 'edit_add_sale'],
            // ['id' => '69', 'name' => 'delete_add_sale'],
            ['id' => '74', 'name' => 'edit_price_sale_screen'],
            // ['id' => '75', 'name' => 'edit_price_pos_screen'],
            ['id' => '76', 'name' => 'edit_discount_sale_screen'],
            // ['id' => '77', 'name' => 'edit_discount_pos_screen'],
            ['id' => '78', 'name' => 'shipment_access'],
            ['id' => '79', 'name' => 'view_product_cost_is_sale_screed'],
            // ['id' => '80', 'name' => 'view_own_sale'],
            ['id' => '82', 'name' => 'discounts'],
            ['id' => '83', 'name' => 'sales_report'],
            ['id' => '84', 'name' => 'sales_return_report'],
            ['id' => '86', 'name' => 'cash_register_report'],
            ['id' => '87', 'name' => 'sale_representative_report'],
            ['id' => '88', 'name' => 'register_view'],
            ['id' => '89', 'name' => 'register_close'],
            ['id' => '90', 'name' => 'another_register_close'],
            ['id' => '96', 'name' => 'general_settings'],
            ['id' => '97', 'name' => 'payment_settings'],
            ['id' => '100', 'name' => 'barcode_settings'],
            ['id' => '102', 'name' => 'view_dashboard_data'],

            ['id' => '121', 'name' => 'process_view'],
            ['id' => '122', 'name' => 'process_add'],
            ['id' => '123', 'name' => 'process_edit'],
            ['id' => '124', 'name' => 'process_delete'],
            ['id' => '125', 'name' => 'production_view'],
            ['id' => '126', 'name' => 'production_add'],
            ['id' => '127', 'name' => 'production_edit'],
            ['id' => '128', 'name' => 'production_delete'],
            ['id' => '130', 'name' => 'manufacturing_report'],

            ['id' => '152', 'name' => 'today_summery'],
            ['id' => '153', 'name' => 'communication'],

            // TODO:: These permission are required for app, but need to add on Create+Update permissions page
            ['id' => '155', 'name' => 'email_setting_index'],
            ['id' => '156', 'name' => 'email_setting_create'],
            ['id' => '157', 'name' => 'email_setting_view'],
            ['id' => '158', 'name' => 'email_setting_update'],
            ['id' => '159', 'name' => 'email_setting_delete'],
            ['id' => '160', 'name' => 'sms_setting_index'],
            ['id' => '161', 'name' => 'sms_setting_create'],
            ['id' => '162', 'name' => 'sms_setting_view'],
            ['id' => '163', 'name' => 'sms_setting_update'],
            ['id' => '164', 'name' => 'sms_setting_delete'],

            ['id' => '180', 'name' => 'sold_product_report'],
            ['id' => '181', 'name' => 'sales_order_report'],
            ['id' => '182', 'name' => 'sales_ordered_products_report'],
            ['id' => '183', 'name' => 'sales_returned_products_report'],
            ['id' => '184', 'name' => 'received_against_sales_report'],
            ['id' => '185', 'name' => 'create_sales_return'],
            ['id' => '186', 'name' => 'edit_sales_return'],
            ['id' => '187', 'name' => 'delete_sales_return'],
            ['id' => '188', 'name' => 'sales_return_index'],
            ['id' => '189', 'name' => 'sold_product_list'],
            ['id' => '191', 'name' => 'sales_order_to_invoice'],
            ['id' => '192', 'name' => 'product_import'],
            ['id' => '193', 'name' => 'expired_product_list'],
            ['id' => '194', 'name' => 'manage_price_group'],
            ['id' => '196', 'name' => 'product_category_index'],
            ['id' => '197', 'name' => 'product_category_add'],
            ['id' => '198', 'name' => 'product_category_edit'],
            ['id' => '199', 'name' => 'product_category_delete'],
            ['id' => '200', 'name' => 'product_brand_index'],
            ['id' => '201', 'name' => 'product_brand_add'],
            ['id' => '202', 'name' => 'product_brand_edit'],
            ['id' => '203', 'name' => 'product_brand_delete'],
            ['id' => '204', 'name' => 'product_unit_index'],
            ['id' => '205', 'name' => 'product_unit_add'],
            ['id' => '206', 'name' => 'product_unit_edit'],
            ['id' => '207', 'name' => 'product_unit_delete'],
            ['id' => '208', 'name' => 'product_variant_index'],
            ['id' => '209', 'name' => 'product_variant_add'],
            ['id' => '210', 'name' => 'product_variant_edit'],
            ['id' => '211', 'name' => 'product_variant_delete'],
            ['id' => '212', 'name' => 'product_warranty_index'],
            ['id' => '213', 'name' => 'product_warranty_add'],
            ['id' => '214', 'name' => 'product_warranty_edit'],
            ['id' => '215', 'name' => 'product_warranty_delete'],
            ['id' => '216', 'name' => 'product_expired_list'],

            ['id' => '217', 'name' => 'purchase_order_index'],
            ['id' => '218', 'name' => 'purchase_order_add'],
            ['id' => '219', 'name' => 'purchase_order_edit'],
            ['id' => '220', 'name' => 'purchase_order_delete'],

            ['id' => '221', 'name' => 'purchase_return_index'],
            ['id' => '222', 'name' => 'purchase_return_add'],
            ['id' => '223', 'name' => 'purchase_return_edit'],
            ['id' => '224', 'name' => 'purchase_return_delete'],

            ['id' => '225', 'name' => 'purchase_report'],
            ['id' => '226', 'name' => 'purchase_order_report'],
            ['id' => '227', 'name' => 'purchase_ordered_product_report'],
            ['id' => '228', 'name' => 'purchase_return_report'],
            ['id' => '229', 'name' => 'purchase_returned_product_report'],
            ['id' => '230', 'name' => 'selling_price_group_index'],
            ['id' => '231', 'name' => 'selling_price_group_add'],
            ['id' => '232', 'name' => 'selling_price_group_edit'],
            ['id' => '233', 'name' => 'selling_price_group_delete'],

            ['id' => '234', 'name' => 'purchased_product_list'],
            ['id' => '235', 'name' => 'stock_adjustment_product_report'],

            ['id' => '236', 'name' => 'money_receipt_index'],
            ['id' => '237', 'name' => 'money_receipt_add'],
            ['id' => '238', 'name' => 'money_receipt_edit'],
            ['id' => '239', 'name' => 'money_receipt_delete'],

            ['id' => '240', 'name' => 'banks_index'],
            ['id' => '241', 'name' => 'banks_create'],
            ['id' => '242', 'name' => 'banks_edit'],
            ['id' => '243', 'name' => 'banks_delete'],

            ['id' => '244', 'name' => 'account_groups_index'],
            ['id' => '245', 'name' => 'account_groups_create'],
            ['id' => '246', 'name' => 'account_groups_edit'],
            ['id' => '247', 'name' => 'account_groups_delete'],

            ['id' => '248', 'name' => 'accounts_index'],
            ['id' => '249', 'name' => 'accounts_create'],
            ['id' => '250', 'name' => 'accounts_edit'],
            ['id' => '251', 'name' => 'accounts_delete'],
            ['id' => '252', 'name' => 'accounts_ledger'],
            ['id' => '253', 'name' => 'capital_accounts_index'],
            ['id' => '254', 'name' => 'duties_and_taxes_index'],

            ['id' => '255', 'name' => 'receipts_index'],
            ['id' => '256', 'name' => 'receipts_create'],
            ['id' => '257', 'name' => 'receipts_edit'],
            ['id' => '258', 'name' => 'receipts_delete'],

            ['id' => '259', 'name' => 'payments_index'],
            ['id' => '260', 'name' => 'payments_create'],
            ['id' => '261', 'name' => 'payments_edit'],
            ['id' => '262', 'name' => 'payments_delete'],

            ['id' => '263', 'name' => 'expenses_index'],
            ['id' => '264', 'name' => 'expenses_create'],
            ['id' => '265', 'name' => 'expenses_edit'],
            ['id' => '266', 'name' => 'expenses_delete'],

            ['id' => '267', 'name' => 'contras_index'],
            ['id' => '268', 'name' => 'contras_create'],
            ['id' => '269', 'name' => 'contras_edit'],
            ['id' => '270', 'name' => 'contras_delete'],

            ['id' => '271', 'name' => 'profit_loss'],
            ['id' => '272', 'name' => 'financial_report'],
            ['id' => '275', 'name' => 'trial_balance'],
            ['id' => '276', 'name' => 'cash_flow'],

            ['id' => '277', 'name' => 'transfer_stock_index'],
            ['id' => '278', 'name' => 'transfer_stock_create'],
            ['id' => '279', 'name' => 'transfer_stock_edit'],
            ['id' => '280', 'name' => 'transfer_stock_delete'],

            ['id' => '281', 'name' => 'transfer_stock_receive_from_warehouse'],
            ['id' => '282', 'name' => 'transfer_stock_receive_from_branch'],

            ['id' => '283', 'name' => 'leaves_index'],
            ['id' => '284', 'name' => 'leaves_create'],
            ['id' => '285', 'name' => 'leaves_edit'],
            ['id' => '286', 'name' => 'leaves_delete'],

            ['id' => '287', 'name' => 'leave_types_index'],
            ['id' => '288', 'name' => 'leave_types_create'],
            ['id' => '289', 'name' => 'leave_types_edit'],
            ['id' => '290', 'name' => 'leave_types_delete'],

            ['id' => '291', 'name' => 'shifts_index'],
            ['id' => '293', 'name' => 'shifts_create'],
            ['id' => '294', 'name' => 'shifts_edit'],
            ['id' => '295', 'name' => 'shifts_delete'],

            ['id' => '296', 'name' => 'attendances_index'],
            ['id' => '297', 'name' => 'attendances_create'],
            ['id' => '298', 'name' => 'attendances_edit'],
            ['id' => '299', 'name' => 'attendances_delete'],

            ['id' => '308', 'name' => 'holidays_index'],
            ['id' => '309', 'name' => 'holidays_create'],
            ['id' => '310', 'name' => 'holidays_edit'],
            ['id' => '311', 'name' => 'holidays_delete'],

            ['id' => '312', 'name' => 'departments_index'],
            ['id' => '314', 'name' => 'departments_create'],
            ['id' => '315', 'name' => 'departments_edit'],
            ['id' => '316', 'name' => 'departments_delete'],

            ['id' => '317', 'name' => 'designations_index'],
            ['id' => '318', 'name' => 'designations_create'],
            ['id' => '319', 'name' => 'designations_edit'],
            ['id' => '320', 'name' => 'designations_delete'],

            ['id' => '321', 'name' => 'payrolls_index'],
            ['id' => '322', 'name' => 'payrolls_create'],
            ['id' => '323', 'name' => 'payrolls_edit'],
            ['id' => '324', 'name' => 'payrolls_delete'],

            ['id' => '325', 'name' => 'payroll_payments_index'],
            ['id' => '326', 'name' => 'payroll_payments_create'],
            ['id' => '327', 'name' => 'payroll_payments_edit'],
            ['id' => '328', 'name' => 'payroll_payments_delete'],

            ['id' => '329', 'name' => 'payroll_report'],
            ['id' => '330', 'name' => 'payroll_payment_report'],
            ['id' => '331', 'name' => 'attendance_report'],

            ['id' => '332', 'name' => 'hrm_dashboard'],

            ['id' => '333', 'name' => 'business_or_shop_settings'],
            ['id' => '334', 'name' => 'dashboard_settings'],
            ['id' => '335', 'name' => 'product_settings'],
            ['id' => '336', 'name' => 'purchase_settings'],
            ['id' => '337', 'name' => 'manufacturing_settings'],
            ['id' => '338', 'name' => 'add_sale_settings'],
            ['id' => '339', 'name' => 'pos_sale_settings'],
            ['id' => '340', 'name' => 'prefix_settings'],
            ['id' => '341', 'name' => 'invoice_layout_settings'],
            ['id' => '342', 'name' => 'print_settings'],
            ['id' => '343', 'name' => 'system_settings'],
            ['id' => '344', 'name' => 'reward_point_settings'],
            ['id' => '345', 'name' => 'send_email_settings'],
            ['id' => '346', 'name' => 'send_sms_settings'],

            ['id' => '347', 'name' => 'warehouses_index'],
            ['id' => '348', 'name' => 'warehouses_add'],
            ['id' => '349', 'name' => 'warehouses_edit'],
            ['id' => '350', 'name' => 'warehouses_delete'],

            ['id' => '351', 'name' => 'payment_methods_index'],
            ['id' => '352', 'name' => 'payment_methods_add'],
            ['id' => '353', 'name' => 'payment_methods_edit'],
            ['id' => '354', 'name' => 'payment_methods_delete'],
            ['id' => '355', 'name' => 'payment_methods_settings'],

            ['id' => '356', 'name' => 'invoice_layouts_index'],
            ['id' => '357', 'name' => 'invoice_layouts_add'],
            ['id' => '358', 'name' => 'invoice_layouts_edit'],
            ['id' => '359', 'name' => 'invoice_layouts_delete'],

            ['id' => '360', 'name' => 'cash_counters_index'],
            ['id' => '361', 'name' => 'cash_counters_add'],
            ['id' => '362', 'name' => 'cash_counters_edit'],
            ['id' => '363', 'name' => 'cash_counters_delete'],

            ['id' => '364', 'name' => 'billing_index'],
            ['id' => '365', 'name' => 'billing_upgrade_plan'],
            ['id' => '366', 'name' => 'billing_branch_add'],
            ['id' => '367', 'name' => 'billing_renew_branch'],

            ['id' => '368', 'name' => 'module_settings'],
            ['id' => '369', 'name' => 'has_access_to_all_area'],
            ['id' => '370', 'name' => 'billing_business_add'],
            ['id' => '371', 'name' => 'billing_pay_due_payment'],

            ['id' => '372', 'name' => 'branches_index'],
            ['id' => '373', 'name' => 'branches_create'],
            ['id' => '374', 'name' => 'branches_edit'],
            ['id' => '375', 'name' => 'branches_delete'],

            ['id' => '376', 'name' => 'accounts_bank_account_create'],
            ['id' => '377', 'name' => 'supplier_manage'],
            ['id' => '378', 'name' => 'customer_manage'],

            ['id' => '379', 'name' => 'todo_index'],
            ['id' => '380', 'name' => 'todo_create'],
            ['id' => '381', 'name' => 'todo_edit'],
            ['id' => '382', 'name' => 'todo_change_status'],
            ['id' => '383', 'name' => 'todo_delete'],

            ['id' => '384', 'name' => 'workspaces_index'],
            ['id' => '385', 'name' => 'workspaces_create'],
            ['id' => '386', 'name' => 'workspaces_edit'],
            ['id' => '387', 'name' => 'workspaces_manage_task'],
            ['id' => '388', 'name' => 'workspaces_delete'],

            ['id' => '393', 'name' => 'messages_index'],
            ['id' => '394', 'name' => 'messages_create'],
            ['id' => '396', 'name' => 'messages_delete'],

            ['id' => '397', 'name' => 'user_activities_log_index'],
            ['id' => '398', 'name' => 'user_activities_log_only_own_log'],
            ['id' => '399', 'name' => 'vat_tax_report'],

            ['id' => '400', 'name' => 'stock_issues_index'],
            ['id' => '401', 'name' => 'stock_issues_products_index'],
            ['id' => '402', 'name' => 'stock_issues_add'],
            ['id' => '403', 'name' => 'stock_issues_edit'],
            ['id' => '404', 'name' => 'stock_issues_delete'],

            ['id' => '405', 'name' => 'expense_report'],
            ['id' => '406', 'name' => 'day_book'],
            ['id' => '407', 'name' => 'purchase_order_to_invoice'],

            ['id' => '408', 'name' => 'allowances_and_deductions_index'],
            ['id' => '409', 'name' => 'allowances_and_deductions_create'],
            ['id' => '410', 'name' => 'allowances_and_deductions_edit'],
            ['id' => '411', 'name' => 'allowances_and_deductions_delete'],

            ['id' => '412', 'name' => 'status_index'],
            ['id' => '413', 'name' => 'status_create'],
            ['id' => '414', 'name' => 'status_edit'],
            ['id' => '415', 'name' => 'status_delete'],

            ['id' => '416', 'name' => 'devices_index'],
            ['id' => '417', 'name' => 'devices_create'],
            ['id' => '418', 'name' => 'devices_edit'],
            ['id' => '419', 'name' => 'devices_delete'],

            ['id' => '420', 'name' => 'device_models_index'],
            ['id' => '421', 'name' => 'device_models_create'],
            ['id' => '422', 'name' => 'device_models_edit'],
            ['id' => '423', 'name' => 'device_models_delete'],

            ['id' => '424', 'name' => 'servicing_settings'],
            ['id' => '425', 'name' => 'job_card_pdf_print_label_settings'],

            ['id' => '426', 'name' => 'job_cards_index'],
            ['id' => '427', 'name' => 'job_cards_create'],
            ['id' => '428', 'name' => 'job_cards_edit'],
            ['id' => '429', 'name' => 'job_cards_delete'],
            ['id' => '430', 'name' => 'job_cards_generate_pdf'],
            ['id' => '431', 'name' => 'job_cards_generate_label'],
            ['id' => '432', 'name' => 'job_cards_change_status'],

            ['id' => '433', 'name' => 'service_invoices_index'],
            ['id' => '434', 'name' => 'service_invoices_create'],
            ['id' => '435', 'name' => 'service_invoices_edit'],
            ['id' => '436', 'name' => 'service_invoices_delete'],

            ['id' => '437', 'name' => 'supplier_ledger'],
            ['id' => '438', 'name' => 'customer_ledger'],

            // ['id' => '439', 'name' => 'service_invoices_only_own'],

            ['id' => '440', 'name' => 'service_quotations_index'],
            // ['id' => '441', 'name' => 'service_quotations_only_own'],
            ['id' => '443', 'name' => 'service_quotations_create'],
            ['id' => '444', 'name' => 'service_quotations_edit'],
            ['id' => '445', 'name' => 'service_quotations_delete'],

            ['id' => '446', 'name' => 'sale_quotations_index'],
            // ['id' => '447', 'name' => 'sale_quotations_only_own'],
            ['id' => '448', 'name' => 'sale_quotations_edit'],
            ['id' => '449', 'name' => 'sale_quotations_change_status'],
            ['id' => '450', 'name' => 'sale_quotations_delete'],

            ['id' => '451', 'name' => 'sale_drafts_index'],
            // ['id' => '452', 'name' => 'sale_drafts_only_own'],
            ['id' => '453', 'name' => 'sale_drafts_edit'],
            ['id' => '454', 'name' => 'sale_drafts_delete'],

            ['id' => '455', 'name' => 'sales_orders_index'],
            // ['id' => '456', 'name' => 'sales_orders_only_own'],
            ['id' => '457', 'name' => 'sales_orders_edit'],
            ['id' => '458', 'name' => 'sales_orders_delete'],

            // ['id' => '459', 'name' => 'sales_return_only_own'],

            ['id' => '460', 'name' => 'advertisements_index'],
            ['id' => '461', 'name' => 'advertisements_create'],
            ['id' => '462', 'name' => 'advertisements_edit'],
            ['id' => '463', 'name' => 'advertisements_delete'],

            ['id' => '464', 'name' => 'currencies_index'],
            ['id' => '465', 'name' => 'currencies_create'],
            ['id' => '466', 'name' => 'currencies_edit'],
            ['id' => '467', 'name' => 'currencies_delete'],

            ['id' => '468', 'name' => 'sales_create_by_add_sale'],
            ['id' => '469', 'name' => 'sales_create_by_pos'],
            ['id' => '470', 'name' => 'sales_index'],
            ['id' => '471', 'name' => 'sales_edit'],
            ['id' => '472', 'name' => 'sales_delete'],

            ['id' => '473', 'name' => 'view_only_won_transactions'],

            ['id' => '474', 'name' => 'product_other_stock_details'],
        ];

        return $permissions;
    }
}
