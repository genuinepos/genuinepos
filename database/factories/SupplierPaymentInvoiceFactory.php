<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SupplierPaymentInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\SupplierPaymentInvoice>
 */
final class SupplierPaymentInvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupplierPaymentInvoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'supplier_payment_id' => $this->faker->randomNumber(),
            'purchase_id' => \App\Models\Purchase::factory(),
            'supplier_return_id' => $this->faker->randomNumber(),
            'paid_amount' => $this->faker->randomFloat(),
            'type' => $this->faker->boolean,
        ];
    }
}
