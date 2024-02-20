<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomerPaymentInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\CustomerPaymentInvoice>
 */
final class CustomerPaymentInvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerPaymentInvoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_payment_id' => $this->faker->randomNumber(),
            'sale_id' => \App\Models\Sale::factory(),
            'sale_return_id' => \App\Models\SaleReturn::factory(),
            'paid_amount' => $this->faker->randomFloat(),
            'type' => $this->faker->boolean,
        ];
    }
}
