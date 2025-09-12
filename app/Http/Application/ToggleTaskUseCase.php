<?php

namespace App\Http\Application;

use App\Models\Task;

class ToggleTaskUseCase
{
    /**
     * Toggle the is_done state of a task.
     *
     * @param int $id
     * @return Task|null
     */
    public function __invoke(int $id): ?Task
    {
        $task = Task::find($id);

        if (!$task) {
            return null;
        }

        $task->update([
            'is_done' => !$task->is_done
        ]);

        return $task->load('keywords');
    }
}
