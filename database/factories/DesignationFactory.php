<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hrm\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hrm\Designation>
 */
final class DesignationFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var string
    */
    protected $model = Designation::class;

    /**
    * Define the model's default state.
    *
    * @return array
    */
    public function definition(): array
    {
        return [
            'designation_name' => $this->faker->word,
            'description' => $this->faker->text,
        ];
    }
}
