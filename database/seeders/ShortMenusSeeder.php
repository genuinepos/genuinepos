<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShortMenus\ShortMenu;
use Illuminate\Support\Facades\Schema;
use App\Models\ShortMenus\ShortMenuUser;

class ShortMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        ShortMenuUser::truncate();
        if (ShortMenuUser::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `short_menu_users` AUTO_INCREMENT = 1');
        }

        ShortMenu::truncate();
        if (ShortMenu::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `short_menus` AUTO_INCREMENT = 1');
        }

        Schema::enableForeignKeyConstraints();

        $shortMenus = array(
            array('id' => '1', 'url' => 'categories.index', 'name' => 'Categories', 'icon' => 'fas fa-list', 'permission' => 'product_category_index'),
            array('id' => '3', 'url' => 'brands.index', 'name' => 'Brands', 'icon' => 'fas fa-list', 'permission' => 'product_brand_index'),
            array('id' => '4', 'url' => 'products.index', 'name' => 'Product List', 'icon' => 'fas fa-list', 'permission' => 'product_all'),
            array('id' => '5', 'url' => 'products.create', 'name' => 'Add Product', 'icon' => 'fas fa-plus-circle', 'permission' => 'product_add'),
            array('id' => '6', 'url' => 'product.bulk.variants.index', 'name' => 'Variants', 'icon' => 'fas fa-list', 'permission' => 'product_variant_index'),
            array('id' => '7', 'url' => 'product.import.create', 'name' => 'Import Products', 'icon' => 'fas fa-file-import', 'permission' => 'product_import'),
            array('id' => '8', 'url' => 'selling.price.groups.index', 'name' => 'Price Group', 'icon' => 'fas fa-list', 'permission' => 'selling_price_group_index'),
            array('id' => '9', 'url' => 'barcode.index', 'name' => 'Barcodes', 'icon' => 'fas fa-barcode', 'permission' => 'generate_barcode'),
            array('id' => '10', 'url' => 'warranties.index', 'name' => 'Warranties ', 'icon' => 'fas fa-list', 'permission' => 'product_warranty_index'),
            array('id' => '11', 'url' => 'contacts.manage.supplier.index,1', 'name' => 'Suppliers', 'icon' => 'fas fa-list', 'permission' => 'supplier_all'),
            array('id' => '12', 'url' => 'contacts.suppliers.import.create', 'name' => 'Import Suppliers', 'icon' => 'fas fa-file-import', 'permission' => 'supplier_import'),
            array('id' => '13', 'url' => 'contacts.manage.customer.index,1', 'name' => 'Customers', 'icon' => 'fas fa-list', 'permission' => 'customer_all'),
            array('id' => '14', 'url' => 'contacts.customers.import.create', 'name' => 'Import Customers', 'icon' => 'fas fa-file-import', 'permission' => 'customer_import'),
            array('id' => '15', 'url' => 'purchases.create', 'name' => 'Add Purchase', 'icon' => 'fas fa-plus-circle', 'permission' => 'purchase_add'),
            array('id' => '16', 'url' => 'purchases.index', 'name' => 'Purchase List', 'icon' => 'fas fa-list', 'permission' => 'purchase_all'),
            array('id' => '17', 'url' => 'purchase.orders.create', 'name' => 'Add Purchase Order', 'icon' => 'fas fa-plus-circle', 'permission' => 'purchase_order_add'),
            array('id' => '18', 'url' => 'purchase.orders.index', 'name' => 'P/o List', 'icon' => 'fas fa-list', 'permission' => 'purchase_order_index'),
            array('id' => '19', 'url' => 'purchase.returns.create', 'name' => 'Add Purchase Return', 'icon' => 'fas fa-plus-circle', 'permission' => 'purchase_return_add'),
            array('id' => '20', 'url' => 'purchase.returns.index', 'name' => 'Purchase Return List', 'icon' => 'fas fa-list', 'permission' => 'purchase_return_index'),
            array('id' => '21', 'url' => 'sales.create', 'name' => 'Add Sale', 'icon' => 'fas fa-plus-circle', 'permission' => 'create_add_sale'),
            array('id' => '22', 'url' => 'sales.index', 'name' => 'Add Sale List', 'icon' => 'fas fa-list', 'permission' => 'view_add_sale'),
            array('id' => '23', 'url' => 'sales.pos.create', 'name' => 'POS', 'icon' => 'fas fa-plus-circle', 'permission' => 'pos_add'),
            array('id' => '24', 'url' => 'sales.pos.index', 'name' => 'POS List', 'icon' => 'fas fa-list', 'permission' => 'pos_all'),
            array('id' => '25', 'url' => 'sale.products.index', 'name' => 'Sold Product List', 'icon' => 'fas fa-list', 'permission' => 'sold_product_list'),
            array('id' => '26', 'url' => 'sale.orders.index', 'name' => 'Sales Order List', 'icon' => 'fas fa-list', 'permission' => 'sales_order_list'),
            array('id' => '29', 'url' => 'sale.quotations.index', 'name' => 'Quotation List', 'icon' => 'fas fa-list', 'permission' => 'sale_quotation'),
            array('id' => '30', 'url' => 'sale.drafts.index', 'name' => 'Draft List', 'icon' => 'fas fa-list', 'permission' => 'sale_draft'),
            array('id' => '31', 'url' => 'sale.shipments.index', 'name' => 'Shipment List', 'icon' => 'fas fa-plus-circle', 'permission' => 'shipment_access'),
            array('id' => '32', 'url' => 'sales.discounts.index', 'name' => 'Discounts', 'icon' => 'fas fa-list', 'permission' => 'discounts'),
            array('id' => '33', 'url' => 'sales.returns.create', 'name' => 'Add Sales Return', 'icon' => 'fas fa-plus-circle', 'permission' => 'create_sales_return'),
            array('id' => '35', 'url' => 'sales.returns.index', 'name' => 'Sales Return List', 'icon' => 'fas fa-list', 'permission' => 'sales_return_index'),
            array('id' => '46', 'url' => 'transfer.stocks.create', 'name' => 'Add Transfer Stock', 'icon' => 'fas fa-plus-circle ', 'permission' => 'transfer_stock_create'),
            array('id' => '47', 'url' => 'transfer.stocks.index', 'name' => 'Transfer Stock', 'icon' => 'fas fa-list', 'permission' => 'transfer_stock_index'),
            array('id' => '48', 'url' => 'receive.stock.from.branch.index', 'name' => 'Receive From Warehouse', 'icon' => 'fas fa-list', 'permission' => 'transfer_stock_receive_from_warehouse'),
            array('id' => '49', 'url' => 'receive.stock.from.warehouse.index', 'name' => 'Recieve From Shop/Business', 'icon' => 'fas fa-list', 'permission' => 'transfer_stock_receive_from_branch'),
            array('id' => '50', 'url' => 'stock.adjustments.create', 'name' => 'Add Stock Adjustment', 'icon' => 'fas fa-plus-circle', 'permission' => 'stock_adjustment_add'),
            array('id' => '51', 'url' => 'stock.adjustments.index', 'name' => 'Stock Adjustment List', 'icon' => 'fas fa-list', 'permission' => 'stock_adjustment_all'),
            array('id' => '52', 'url' => 'banks.index', 'name' => 'Banks', 'icon' => 'fas fa-list', 'permission' => 'banks_index'),
            array('id' => '53', 'url' => 'accounts.index', 'name' => 'Accounts', 'icon' => 'fas fa-list', 'permission' => 'accounts_index'),
            array('id' => '54', 'url' => 'receipts.index', 'name' => 'Receipts', 'icon' => 'fas fa-list', 'permission' => 'receipts_index'),
            array('id' => '55', 'url' => 'payments.index', 'name' => 'Payments', 'icon' => 'fas fa-list', 'permission' => 'payments_index'),
            array('id' => '56', 'url' => 'expenses.index', 'name' => 'Expenses', 'icon' => 'fas fa-list', 'permission' => 'expenses_index'),
            array('id' => '57', 'url' => 'contras.index', 'name' => 'Contras', 'icon' => 'fas fa-list', 'permission' => 'contras_index'),
            array('id' => '58', 'url' => 'users.create', 'name' => 'Add User', 'icon' => 'fas fa-plus-circle', 'permission' => 'user_add'),
            array('id' => '59', 'url' => 'users.index', 'name' => 'User List', 'icon' => 'fas fa-list', 'permission' => 'user_view'),
            array('id' => '60', 'url' => 'users.role.create', 'name' => 'Add Role', 'icon' => 'fas fa-plus-circle', 'permission' => 'role_add'),
            array('id' => '61', 'url' => 'users.role.index', 'name' => 'Role List', 'icon' => 'fas fa-list', 'permission' => 'role_view'),
            array('id' => '62', 'url' => 'settings.general.index', 'name' => 'General Settings', 'icon' => 'fas fa-cogs', 'permission' => 'general_settings'),
            array('id' => '63', 'url' => 'warehouses.index', 'name' => 'Warehouses', 'icon' => 'fas fa-list', 'permission' => 'warehouses_index'),
            array('id' => '64', 'url' => 'cash.counters.index', 'name' => 'Cash Counters', 'icon' => 'fas fa-list', 'permission' => 'cash_counters_index')
        );

        \Illuminate\Support\Facades\DB::table('short_menus')->insert($shortMenus);
    }
}
