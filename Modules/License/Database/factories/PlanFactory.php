<?php

namespace Modules\License\Database\factories;

use Modules\License\Entities\PlanType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\License\Entities\Plan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'plan_type_id' => $this->faker->randomElement(PlanType::pluck('id')->toArray()),
            'description' => $this->faker->paragraph(1),
            'price' => $this->faker->randomFloat(),
            'period' => $this->faker->randomElement([1, 6, 12, 24]),
            'status' => 1,
        ];
    }
}

