<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Study_room_acces;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Study_room_acces>
 */
class Study_room_accesFactory extends Factory
{
    protected $modelo = Study_room_acces::class;

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

    public function withParams(int $param_student_id, int $param_mentor_id): Study_room_accesFactory
    {
        return $this->state(function (array $attributes) use ($param_student_id, $param_mentor_id) {
            return [
                'student_id'    => $param_student_id,
                'study_room_id' => $param_mentor_id ,
                'logic_cancel'  => 0
            ];
        });
    }

}
