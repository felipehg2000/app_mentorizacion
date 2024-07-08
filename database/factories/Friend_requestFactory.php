<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Friend_request;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friend_request>
 */
class Friend_requestFactory extends Factory
{
    protected $model = Friend_request::class;
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

    public function withParams(int $param_student_id, int $param_mentor_id, int $param_estado, int $param_seen_mentor, int $param_seen_student): Friend_requestFactory
    {
        return $this->state(function (array $attributes) use ($param_student_id, $param_mentor_id, $param_estado, $param_seen_mentor, $param_seen_student) {
            return [
                'mentor_id'       => $param_mentor_id ,
                'student_id'      => $param_student_id ,
                'status'          => $param_estado ,
                'seen_by_mentor'  => $param_seen_mentor ,
                'seen_by_student' => $param_seen_student
            ];
        });
    }
}
