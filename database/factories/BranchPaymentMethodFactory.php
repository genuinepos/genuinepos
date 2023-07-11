<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\BranchPaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\BranchPaymentMethod>
 */
final class BranchPaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BranchPaymentMethod::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'payment_method_id' => $this->faker->randomNumber(),
            'account_id' => $this->faker->randomNumber(),
        ];
    }
}
