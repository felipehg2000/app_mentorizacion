<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Synchronous_message;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Synchronous_message>
 */
class Synchronous_messageFactory extends Factory
{
    protected $model = Synchronous_message::class;

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

    public function withParams(int $param_student_id, int $param_mentor_id): Synchronous_messageFactory
    {
        return $this->state(function (array $attributes) use ($param_student_id, $param_mentor_id) {
            return [
                'study_room_id'       => $param_mentor_id                                                   ,
                'study_room_acces_id' => $param_student_id                                                  ,
                'sender'              => $this->faker->randomElement([$param_mentor_id, $param_student_id]) ,
                'message'             => $this->faker->sentence()                                           ,
                'seen_by_mentor'      => 0                                                                  ,
                'seen_by_student'     => 0
            ];
        });
    }
}
