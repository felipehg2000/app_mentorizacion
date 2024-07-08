<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mentor;
use App\Models\Study_room;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Study_room>
 */
class Study_roomFactory extends Factory
{
    protected $model = Study_room::class;
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

    public function forMentors(int $param_mentor_id): Study_roomFactory
    {
        return $this->state(function (array $attributes) use ($param_mentor_id) {
            return [
                'MENTOR_ID' => $param_mentor_id,
                'COLOR'     => 'Blue'
            ];
        });
    }
}
