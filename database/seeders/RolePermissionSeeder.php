<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateRolePermissionDataButKeepOldData();
        \Artisan::call('optimize:clear');

        $this->createRolePermission();
        \Artisan::call('optimize:clear');

        $this->syncRolesPermissions();

        $this->call(UserRoleSeeder::class);
    }

    public function truncateRolePermissionDataButKeepOldData(): void
    {
        Schema::disableForeignKeyConstraints();
        if (Role::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `roles` AUTO_INCREMENT = 1');
        }

        Permission::truncate();
        if (Permission::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `permissions` AUTO_INCREMENT = 1');
        }

        Schema::enableForeignKeyConstraints();
    }

    public function createRolePermission(): void
    {
        $roles = $this->getRolesArray();
        foreach ($roles as $role) {
            $roleAlreadyExists = Role::where('name', $role['name'])->exists();
            if (! $roleAlreadyExists) {
                Role::create(['name' => $role['name']]);
            }
        }

        $permissions = $this->getPermissionsArray();
        foreach ($permissions as $permission) {
            $permissionExists = Permission::where('id', $permission['id'])->where('name', $permission['name'])->exists();
            if (! $permissionExists) {
                Permission::create([
                    'id' => $permission['id'],
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                ]);
            }
        }
    }

    public function syncRolesPermissions(): void
    {
        $permissions = Permission::all();
        $role = Role::first();
        $role->syncPermissions($permissions);

        $role = Role::skip(1)->first();
        $role->syncPermissions($permissions);
    }

    public function getRolesArray(): array
    {
        $roles = [
            ['id' => '1', 'name' => 'superadmin'],
            ['id' => '2', 'name' => 'admin'],
            ['id' => '3', 'name' => 'Accountant'],
            ['id' => '4', 'name' => 'POS Seller'],
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
            ['id' => '27', 'name' => 'categories'],
            ['id' => '28', 'name' => 'brand'],
            ['id' => '29', 'name' => 'units'],
            ['id' => '30', 'name' => 'variant'],
            ['id' => '31', 'name' => 'warranties'],
            ['id' => '32', 'name' => 'selling_price_group'],
            ['id' => '33', 'name' => 'generate_barcode'],
            ['id' => '34', 'name' => 'product_settings'],
            ['id' => '35', 'name' => 'stock_report'],
            ['id' => '36', 'name' => 'stock_in_out_report'],
            ['id' => '37', 'name' => 'purchase_all'],
            ['id' => '38', 'name' => 'purchase_add'],
            ['id' => '39', 'name' => 'purchase_edit'],
            ['id' => '40', 'name' => 'purchase_delete'],
            ['id' => '41', 'name' => 'purchase_payment'],
            ['id' => '42', 'name' => 'purchase_return'],
            ['id' => '43', 'name' => 'status_update'],
            ['id' => '44', 'name' => 'purchase_settings'],
            ['id' => '45', 'name' => 'purchase_statements'],
            ['id' => '46', 'name' => 'purchase_sale_report'],
            ['id' => '47', 'name' => 'pro_purchase_report'],
            ['id' => '48', 'name' => 'purchase_payment_report'],
            ['id' => '49', 'name' => 'adjustment_all'],
            ['id' => '50', 'name' => 'adjustment_add_from_location'],
            ['id' => '51', 'name' => 'adjustment_add_from_warehouse'],
            ['id' => '52', 'name' => 'adjustment_delete'],
            ['id' => '53', 'name' => 'stock_adjustment_report'],
            ['id' => '54', 'name' => 'view_expense'],
            ['id' => '55', 'name' => 'add_expense'],
            ['id' => '56', 'name' => 'edit_expense'],
            ['id' => '57', 'name' => 'delete_expense'],
            ['id' => '58', 'name' => 'expense_category'],
            ['id' => '59', 'name' => 'category_wise_expense'],
            ['id' => '60', 'name' => 'expanse_report'],
            ['id' => '61', 'name' => 'pos_all'],
            ['id' => '62', 'name' => 'pos_add'],
            ['id' => '63', 'name' => 'pos_edit'],
            ['id' => '64', 'name' => 'pos_delete'],
            ['id' => '65', 'name' => 'pos_sale_settings'],
            ['id' => '66', 'name' => 'create_add_sale'],
            ['id' => '67', 'name' => 'view_add_sale'],
            ['id' => '68', 'name' => 'edit_add_sale'],
            ['id' => '69', 'name' => 'delete_add_sale'],
            ['id' => '70', 'name' => 'add_sale_settings'],
            ['id' => '71', 'name' => 'sale_draft'],
            ['id' => '72', 'name' => 'sale_quotation'],
            ['id' => '73', 'name' => 'sale_payment'],
            ['id' => '74', 'name' => 'edit_price_sale_screen'],
            ['id' => '75', 'name' => 'edit_price_pos_screen'],
            ['id' => '76', 'name' => 'edit_discount_sale_screen'],
            ['id' => '77', 'name' => 'edit_discount_pos_screen'],
            ['id' => '78', 'name' => 'shipment_access'],
            ['id' => '79', 'name' => 'view_product_cost_is_sale_screed'],
            ['id' => '80', 'name' => 'view_own_sale'],
            ['id' => '81', 'name' => 'return_access'],
            ['id' => '82', 'name' => 'discounts'],
            ['id' => '83', 'name' => 'sale_statements'],
            ['id' => '84', 'name' => 'sale_return_statements'],
            ['id' => '85', 'name' => 'pro_sale_report'],
            ['id' => '86', 'name' => 'sale_payment_report'],
            ['id' => '87', 'name' => 'c_register_report'],
            ['id' => '88', 'name' => 'sale_representative_report'],
            ['id' => '89', 'name' => 'register_view'],
            ['id' => '90', 'name' => 'register_close'],
            ['id' => '91', 'name' => 'another_register_close'],
            ['id' => '92', 'name' => 'tax_report'],
            ['id' => '93', 'name' => 'production_report'],
            ['id' => '94', 'name' => 'tax'],
            ['id' => '95', 'name' => 'branch'],
            ['id' => '96', 'name' => 'warehouse'],
            ['id' => '97', 'name' => 'g_settings'],
            ['id' => '98', 'name' => 'p_settings'],
            ['id' => '99', 'name' => 'inv_sc'],
            ['id' => '100', 'name' => 'inv_lay'],
            ['id' => '101', 'name' => 'barcode_settings'],
            ['id' => '102', 'name' => 'cash_counters'],
            ['id' => '103', 'name' => 'dash_data'],
            ['id' => '104', 'name' => 'ac_access'],
            ['id' => '105', 'name' => 'hrm_dashboard'],
            ['id' => '106', 'name' => 'leave_type'],
            ['id' => '107', 'name' => 'leave_assign'],
            ['id' => '108', 'name' => 'shift'],
            ['id' => '109', 'name' => 'attendance'],
            ['id' => '110', 'name' => 'view_allowance_and_deduction'],
            ['id' => '111', 'name' => 'payroll'],
            ['id' => '112', 'name' => 'holiday'],
            ['id' => '113', 'name' => 'department'],
            ['id' => '114', 'name' => 'designation'],
            ['id' => '115', 'name' => 'payroll_report'],
            ['id' => '116', 'name' => 'payroll_payment_report'],
            ['id' => '117', 'name' => 'attendance_report'],
            ['id' => '118', 'name' => 'assign_todo'],
            ['id' => '119', 'name' => 'work_space'],
            ['id' => '120', 'name' => 'memo'],
            ['id' => '121', 'name' => 'msg'],
            ['id' => '122', 'name' => 'process_view'],
            ['id' => '123', 'name' => 'process_add'],
            ['id' => '124', 'name' => 'process_edit'],
            ['id' => '125', 'name' => 'process_delete'],
            ['id' => '126', 'name' => 'production_view'],
            ['id' => '127', 'name' => 'production_add'],
            ['id' => '128', 'name' => 'production_edit'],
            ['id' => '129', 'name' => 'production_delete'],
            ['id' => '130', 'name' => 'manuf_settings'],
            ['id' => '131', 'name' => 'manuf_report'],
            ['id' => '132', 'name' => 'proj_view'],
            ['id' => '133', 'name' => 'proj_create'],
            ['id' => '134', 'name' => 'proj_edit'],
            ['id' => '135', 'name' => 'proj_delete'],
            ['id' => '136', 'name' => 'ripe_add_invo'],
            ['id' => '137', 'name' => 'ripe_edit_invo'],
            ['id' => '138', 'name' => 'ripe_view_invo'],
            ['id' => '139', 'name' => 'ripe_delete_invo'],
            ['id' => '140', 'name' => 'change_invo_status'],
            ['id' => '141', 'name' => 'ripe_jop_sheet_status'],
            ['id' => '142', 'name' => 'ripe_jop_sheet_add'],
            ['id' => '143', 'name' => 'ripe_jop_sheet_edit'],
            ['id' => '144', 'name' => 'ripe_jop_sheet_delete'],
            ['id' => '145', 'name' => 'ripe_only_assinged_job_sheet'],
            ['id' => '146', 'name' => 'ripe_view_all_job_sheet'],
            ['id' => '147', 'name' => 'superadmin_access_pack_subscrip'],
            ['id' => '148', 'name' => 'e_com_sync_pro_cate'],
            ['id' => '149', 'name' => 'e_com_sync_pro'],
            ['id' => '150', 'name' => 'e_com_sync_order'],
            ['id' => '151', 'name' => 'e_com_map_tax_rate'],
            ['id' => '152', 'name' => 'today_summery'],
            ['id' => '153', 'name' => 'communication'],
            ['id' => '154', 'name' => 'receive_payment_index'],

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

            ['id' => '165', 'name' => 'warehouse_to_business_location__add_transfer'],
            ['id' => '166', 'name' => 'warehouse_to_business_location__transfer_list'],
            ['id' => '167', 'name' => 'warehouse_to_business_location__receive_stock'],

            ['id' => '168', 'name' => 'business_location_to_warehouse__add_transfer'],
            ['id' => '169', 'name' => 'business_location_to_warehouse__transfer_list'],
            ['id' => '170', 'name' => 'business_location_to_warehouse__receive_stock'],

            ['id' => '171', 'name' => 'own_to_other_business_location__add_transfer'],
            ['id' => '172', 'name' => 'own_to_other_business_location__transfer_list'],
            ['id' => '173', 'name' => 'own_to_other_business_location__receive_stock'],
            ['id' => '174', 'name' => 'brand_create'],
            ['id' => '175', 'name' => 'brand_edit'],
            ['id' => '176', 'name' => 'brand_delete'],
            ['id' => '177', 'name' => 'unit_create'],
            ['id' => '178', 'name' => 'unit_edit'],
            ['id' => '179', 'name' => 'unit_delete'],
        ];

        return $permissions;
    }
}
