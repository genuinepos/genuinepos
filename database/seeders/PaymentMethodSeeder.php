<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PaymentMethodSeeder extends Seeder
{
    public function getDefaultPaymentMethod(): array
    {
        return [
            'Cash',
            'Debit-Card',
            'Credit-Card',
            'Cheque',
            'Bank-Transfer',
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        PaymentMethod::truncate();
        if (PaymentMethod::count() == 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE payment_methods AUTO_INCREMENT=1');
        }
        $paymentMethods = $this->getDefaultPaymentMethod();
        foreach ($paymentMethods as $paymentMethod) {
            PaymentMethod::create(['name' => $paymentMethod, 'is_fixed' => 1]);
        }
    }
}
