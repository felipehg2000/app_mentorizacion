<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(array $parametros = []): array
    {
        return [
            //
        ];
    }

    public function forStudents(int $param_usu_id): StudentFactory
    {
        return $this->state(function (array $attributes) use ($param_usu_id) {
            return [
                'USER_ID' => $param_usu_id,
                'career'     => $this->faker->word,
                'first_year' => $this->faker->year,
                'duration'   => $this->faker->numberBetween(1, 5),
            ];
        });
    }
}
