<?php

namespace Modules\SAAS\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\SAAS\Entities\Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph(1),
            'price' => $this->faker->randomFloat(),
            'period_month' => $this->faker->randomElement([1, 6, 12, 24]),
            'status' => 1,
        ];
    }
}
