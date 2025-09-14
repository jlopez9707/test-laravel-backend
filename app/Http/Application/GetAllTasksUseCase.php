<?php

namespace App\Http\Application;

use App\Models\Task;

class GetAllTasksUseCase
{
    /**
     * Get all tasks with their keywords.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function __invoke()
    {
        return Task::with('keywords')->orderBy('id', 'asc')->get();
    }
}
