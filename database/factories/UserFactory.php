<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'       => $this->faker->firstName             ,
            'surname'    => $this->faker->lastName              ,
            'email'      => $this->faker->unique()->safeEmail   ,
            'user'       => $this->faker->unique()->userName    ,
            'password'   => 'G/mHIcX5C3Ex2kXgMVO50Q=='          ,
            'user_type'  => $this->faker->numberBetween(1, 3)   ,
            'study_area' => $this->faker->numberBetween(1, 4)   ,
            'description'=> $this->faker->paragraph()           ,
            'banned'     => $this->faker->boolean(10)
    ];
    }
}
