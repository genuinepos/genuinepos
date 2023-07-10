<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Supplier>
 */
final class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'contact_id' => $this->faker->word,
            'name' => $this->faker->name,
            'business_name' => $this->faker->word,
            'phone' => $this->faker->phoneNumber,
            'alternative_phone' => $this->faker->word,
            'alternate_phone' => $this->faker->word,
            'landline' => $this->faker->word,
            'email' => $this->faker->safeEmail,
            'date_of_birth' => $this->faker->word,
            'tax_number' => $this->faker->word,
            'opening_balance' => $this->faker->randomFloat(),
            'pay_term' => $this->faker->boolean,
            'pay_term_number' => $this->faker->randomNumber(),
            'address' => $this->faker->address,
            'shipping_address' => $this->faker->text,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
            'zip_code' => $this->faker->word,
            'total_purchase' => $this->faker->randomFloat(),
            'total_paid' => $this->faker->randomFloat(),
            'total_less' => $this->faker->randomFloat(),
            'total_purchase_due' => $this->faker->randomFloat(),
            'total_return' => $this->faker->randomFloat(),
            'total_purchase_return_due' => $this->faker->randomFloat(),
            'status' => $this->faker->boolean,
            'prefix' => $this->faker->word,
        ];
    }
}
