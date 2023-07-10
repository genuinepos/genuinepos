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
        foreach ($roles as $k => $role) {
            $roleAlreadyExists = Role::where('name', $role['name'])->exists();
            if (! $roleAlreadyExists) {
                Role::create(['name' => $role['name']]);
            }
        }

        $permissions = $this->getPermissionsArray();
        foreach ($permissions as $k => $permission) {
            Permission::create([
                'id' => $permission['id'],
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'] ?? 'web',
            ]);
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
            ['id' => '1', 'name' => 'superadmin', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '2', 'name' => 'admin', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '3', 'name' => 'Accountant', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '4', 'name' => 'POS Seller', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
        ];

        return $roles;
    }

    public function getPermissionsArray(): array
    {
        $permissions = [
            ['id' => '1', 'name' => 'user_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '2', 'name' => 'user_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '3', 'name' => 'user_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '4', 'name' => 'user_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '5', 'name' => 'role_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '6', 'name' => 'role_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '7', 'name' => 'role_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '8', 'name' => 'role_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '9', 'name' => 'supplier_all', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '10', 'name' => 'supplier_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '11', 'name' => 'supplier_import', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:37', 'updated_at' => '2022-11-22 10:42:37'],
            ['id' => '12', 'name' => 'supplier_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '13', 'name' => 'supplier_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '14', 'name' => 'customer_all', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '15', 'name' => 'customer_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '16', 'name' => 'customer_import', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '17', 'name' => 'customer_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '18', 'name' => 'customer_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '19', 'name' => 'customer_group', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '20', 'name' => 'customer_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '21', 'name' => 'supplier_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '22', 'name' => 'product_all', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '23', 'name' => 'product_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '24', 'name' => 'product_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '25', 'name' => 'openingStock_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '26', 'name' => 'product_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '27', 'name' => 'categories', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '28', 'name' => 'brand', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '29', 'name' => 'units', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '30', 'name' => 'variant', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '31', 'name' => 'warranties', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '32', 'name' => 'selling_price_group', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '33', 'name' => 'generate_barcode', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '34', 'name' => 'product_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '35', 'name' => 'stock_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '36', 'name' => 'stock_in_out_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '37', 'name' => 'purchase_all', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '38', 'name' => 'purchase_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '39', 'name' => 'purchase_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '40', 'name' => 'purchase_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '41', 'name' => 'purchase_payment', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '42', 'name' => 'purchase_return', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '43', 'name' => 'status_update', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '44', 'name' => 'purchase_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '45', 'name' => 'purchase_statements', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '46', 'name' => 'purchase_sale_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '47', 'name' => 'pro_purchase_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '48', 'name' => 'purchase_payment_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '49', 'name' => 'adjustment_all', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '50', 'name' => 'adjustment_add_from_location', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '51', 'name' => 'adjustment_add_from_warehouse', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '52', 'name' => 'adjustment_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '53', 'name' => 'stock_adjustment_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '54', 'name' => 'view_expense', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '55', 'name' => 'add_expense', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '56', 'name' => 'edit_expense', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '57', 'name' => 'delete_expense', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '58', 'name' => 'expense_category', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '59', 'name' => 'category_wise_expense', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '60', 'name' => 'expanse_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '61', 'name' => 'pos_all', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '62', 'name' => 'pos_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '63', 'name' => 'pos_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '64', 'name' => 'pos_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '65', 'name' => 'pos_sale_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '66', 'name' => 'create_add_sale', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '67', 'name' => 'view_add_sale', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '68', 'name' => 'edit_add_sale', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '69', 'name' => 'delete_add_sale', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '70', 'name' => 'add_sale_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '71', 'name' => 'sale_draft', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '72', 'name' => 'sale_quotation', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '73', 'name' => 'sale_payment', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '74', 'name' => 'edit_price_sale_screen', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '75', 'name' => 'edit_price_pos_screen', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '76', 'name' => 'edit_discount_sale_screen', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '77', 'name' => 'edit_discount_pos_screen', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '78', 'name' => 'shipment_access', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '79', 'name' => 'view_product_cost_is_sale_screed', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '80', 'name' => 'view_own_sale', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '81', 'name' => 'return_access', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '82', 'name' => 'discounts', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '83', 'name' => 'sale_statements', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '84', 'name' => 'sale_return_statements', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '85', 'name' => 'pro_sale_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '86', 'name' => 'sale_payment_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '87', 'name' => 'c_register_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '88', 'name' => 'sale_representative_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '89', 'name' => 'register_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '90', 'name' => 'register_close', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:39', 'updated_at' => '2022-11-22 10:42:39'],
            ['id' => '91', 'name' => 'another_register_close', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '92', 'name' => 'tax_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '93', 'name' => 'production_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '94', 'name' => 'tax', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '95', 'name' => 'branch', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '96', 'name' => 'warehouse', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '97', 'name' => 'g_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '98', 'name' => 'p_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '99', 'name' => 'inv_sc', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '100', 'name' => 'inv_lay', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '101', 'name' => 'barcode_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '102', 'name' => 'cash_counters', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '103', 'name' => 'dash_data', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '104', 'name' => 'ac_access', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '105', 'name' => 'hrm_dashboard', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '106', 'name' => 'leave_type', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '107', 'name' => 'leave_assign', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '108', 'name' => 'shift', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '109', 'name' => 'attendance', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '110', 'name' => 'view_allowance_and_deduction', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '111', 'name' => 'payroll', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '112', 'name' => 'holiday', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '113', 'name' => 'department', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '114', 'name' => 'designation', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '115', 'name' => 'payroll_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '116', 'name' => 'payroll_payment_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '117', 'name' => 'attendance_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '118', 'name' => 'assign_todo', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '119', 'name' => 'work_space', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:40', 'updated_at' => '2022-11-22 10:42:40'],
            ['id' => '120', 'name' => 'memo', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '121', 'name' => 'msg', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '122', 'name' => 'process_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '123', 'name' => 'process_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '124', 'name' => 'process_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '125', 'name' => 'process_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '126', 'name' => 'production_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '127', 'name' => 'production_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '128', 'name' => 'production_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '129', 'name' => 'production_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '130', 'name' => 'manuf_settings', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '131', 'name' => 'manuf_report', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '132', 'name' => 'proj_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '133', 'name' => 'proj_create', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '134', 'name' => 'proj_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '135', 'name' => 'proj_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '136', 'name' => 'ripe_add_invo', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '137', 'name' => 'ripe_edit_invo', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '138', 'name' => 'ripe_view_invo', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '139', 'name' => 'ripe_delete_invo', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '140', 'name' => 'change_invo_status', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '141', 'name' => 'ripe_jop_sheet_status', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '142', 'name' => 'ripe_jop_sheet_add', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '143', 'name' => 'ripe_jop_sheet_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '144', 'name' => 'ripe_jop_sheet_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:41', 'updated_at' => '2022-11-22 10:42:41'],
            ['id' => '145', 'name' => 'ripe_only_assinged_job_sheet', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '146', 'name' => 'ripe_view_all_job_sheet', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '147', 'name' => 'superadmin_access_pack_subscrip', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '148', 'name' => 'e_com_sync_pro_cate', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '149', 'name' => 'e_com_sync_pro', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '150', 'name' => 'e_com_sync_order', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '151', 'name' => 'e_com_map_tax_rate', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '152', 'name' => 'today_summery', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '153', 'name' => 'communication', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '154', 'name' => 'receive_payment_index', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],

            // TODO:: These permission are required for app, but need to add on Create+Update permissions page
            ['id' => '155', 'name' => 'email_setting_index', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '156', 'name' => 'email_setting_create', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '157', 'name' => 'email_setting_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '158', 'name' => 'email_setting_update', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '159', 'name' => 'email_setting_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],

            ['id' => '160', 'name' => 'sms_setting_index', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '161', 'name' => 'sms_setting_create', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '162', 'name' => 'sms_setting_view', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '163', 'name' => 'sms_setting_update', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '164', 'name' => 'sms_setting_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],

            ['id' => '165', 'name' => 'warehouse_to_business_location__add_transfer', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '166', 'name' => 'warehouse_to_business_location__transfer_list', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '167', 'name' => 'warehouse_to_business_location__receive_stock', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],

            ['id' => '168', 'name' => 'business_location_to_warehouse__add_transfer', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '169', 'name' => 'business_location_to_warehouse__transfer_list', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '170', 'name' => 'business_location_to_warehouse__receive_stock', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],

            ['id' => '171', 'name' => 'own_to_other_business_location__add_transfer', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '172', 'name' => 'own_to_other_business_location__transfer_list', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '173', 'name' => 'own_to_other_business_location__receive_stock', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:42', 'updated_at' => '2022-11-22 10:42:42'],
            ['id' => '174', 'name' => 'brand_create', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '175', 'name' => 'brand_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '176', 'name' => 'brand_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '177', 'name' => 'unit_create', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '178', 'name' => 'unit_edit', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
            ['id' => '179', 'name' => 'unit_delete', 'guard_name' => 'web', 'created_at' => '2022-11-22 10:42:38', 'updated_at' => '2022-11-22 10:42:38'],
        ];

        return $permissions;
    }
}
