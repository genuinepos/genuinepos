<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PaymentMethodSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\PaymentMethodSetting>
 */
final class PaymentMethodSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentMethodSetting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'payment_method_id' => $this->faker->randomNumber(),
            'branch_id' => $this->faker->randomNumber(),
            'account_id' => $this->faker->randomNumber(),
        ];
    }
}
