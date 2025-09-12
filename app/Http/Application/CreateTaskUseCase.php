<?php

namespace App\Http\Application;

use App\Models\Task;

class CreateTaskUseCase
{
    /**
     * Create a new task and assign existing keywords.
     *
     * @param string $title
     * @param array|null $keywordIds
     * @return Task
     */
    public function __invoke(string $title, ?array $keywordIds = null): Task
    {
        $task = Task::create([
            'title' => $title,
            'is_done' => false
        ]);

        if ($keywordIds && is_array($keywordIds)) {
            $task->keywords()->attach($keywordIds);
        }

        return $task->load('keywords');
    }
}
