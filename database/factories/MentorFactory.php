<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Mentor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mentor>
 */
class MentorFactory extends Factory
{
    protected $model = Mentor::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function forMentors(int $param_usu_id): MentorFactory
    {
        return $this->state(function (array $attributes) use ($param_usu_id) {
            return [
                'USER_ID' => $param_usu_id,
                'COMPANY' => $this->faker->word,
                'JOB'     => $this->faker->word
            ];
        });
    }
}
