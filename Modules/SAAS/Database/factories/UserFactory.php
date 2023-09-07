<?php

namespace Modules\SAAS\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\SAAS\Entities\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail(),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'phone' => $this->faker->phoneNumber(),
            'photo' => $this->faker->imageUrl(),
            'address' => $this->faker->address(),
            'language' => 'en',
            'currency' => 'USD',
        ];
    }
}
