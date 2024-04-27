<?php

namespace Modules\SAAS\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(array $request)
    {
        $admin = [
            'name' => $request['fullname'],
            'emp_id' => '1001',
            'username' => strtolower(str_replace(' ', '', str_replace('.', '', $request['fullname']))),
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'shift_id' => null,
            'role_type' => 1,
            'allow_login' => 1,
            'status' => 1,
            'phone' => 'XXXXXXXXX',
            'date_of_birth' => '0000-00-00',
            'photo' => null,
            'language' => 'en',
            'created_at' => Carbon::now(),
            'updated_at' => null,
        ];

        return $admin;
    }
}
