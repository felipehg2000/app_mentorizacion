<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

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

    public function forMentors(int $param_study_room_id): TaskFactory
    {
        return $this->state(function (array $attributes) use ($param_study_room_id) {
            return [
                'STUDY_ROOM_ID' => $param_study_room_id, // Generar una sala de estudio asociada
                'TASK_TITLE'    => $this->faker->sentence,
                'DESCRIPTION'   => $this->faker->paragraph,
                'LAST_DAY'      => $this->faker->dateTimeBetween('now', '+1 month'),
                'LOGIC_CANCEL'  => $this->faker->boolean(10), // 10% de posibilidad de estar cancelada
                'created_at'    => now(),
            ];
        });
    }
}
