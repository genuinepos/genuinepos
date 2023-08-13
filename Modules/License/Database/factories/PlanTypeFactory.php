<?php

namespace Modules\License\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlanTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\License\Entities\PlanType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(1),
            'description' => $this->faker->paragraph(2),
            'status' => true
        ];
    }
}

