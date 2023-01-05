<?php

namespace Database\Seeders;

use App\Models\InvoiceLayout;
use Exception;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        try {
            echo "Seeding Default Data" . PHP_EOL;
            $this->call(GeneralSettingsSeeder::class);
            $this->call(CurrencySeeder::class);
            $this->call(AddonsSeeder::class);
            $this->call(ShortMenusSeeder::class);
            $this->call(PosShortMenusSeeder::class);
            $this->call(UserRoleSeeder::class);
            $this->call(DefaultUsersSeeder::class);
            $this->call(RolePermissionSeeder::class);
            $this->call(BarcodeSettingsSeeder::class);
            $this->call(InvoiceLayoutSeeder::class);
            $this->call(InvoiceSchemaSeeder::class);
            $this->call(UnitSeeder::class);
            $this->call(AccountSeeder::class);
            $this->call(PermissionSeeder::class);
            $mailSettings = '{"MAIL_MAILER":"smtp","MAIL_HOST":"smtp.gmail.com","MAIL_PORT":"587","MAIL_USERNAME":"s1@gmail.com","MAIL_PASSWORD":"speeddigit@54321","MAIL_ENCRYPTION":"tls","MAIL_FROM_ADDRESS":"s1@gmail.com","MAIL_FROM_NAME":"SpeedDigit","MAIL_ACTIVE":true}';

            $posOldSetting = '{"is_disable_draft":0,"is_disable_quotation":0,"is_disable_challan":0,"is_disable_hold_invoice":0,"is_disable_multiple_pay":1,"is_show_recent_transactions":0,"is_disable_discount":0,"is_disable_order_tax":0,"is_show_credit_sale_button":1,"is_show_partial_sale_button":1,"is_enabled_draft":1,"is_enabled_quotation":1,"is_enabled_hold_invoice":1,"is_enabled_suspend":1,"is_enabled_discount":1}';

            $posNewSetting = '{"is_enabled_multiple_pay":1,"is_enabled_draft":1,"is_enabled_quotation":1,"is_enabled_suspend":1,"is_enabled_discount":1,"is_enabled_order_tax":1,"is_show_recent_transactions":1,"is_enabled_credit_full_sale":1,"is_enabled_hold_invoice":1}';

            
        } catch (Exception $e) {
            dd($e->getMessage());
        } finally {
            echo "Operation finished." . PHP_EOL;
        }
    }
}
