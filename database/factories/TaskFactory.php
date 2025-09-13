<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title'   => $this->faker->sentence(4),
            'is_done' => $this->faker->boolean(20),
        ];
    }
}
