<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Return all tasks with their keywords.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Task::with('keywords')->get();

        return response()->json([
            'success' => true,
            'data' => $tasks
        ]);
    }

    /**
     * Create a new task and assign existing keywords (by IDs).
     *
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create([
            'title' => $request->title,
            'is_done' => false
        ]);

        if ($request->has('keyword_ids') && is_array($request->keyword_ids)) {
            $task->keywords()->attach($request->keyword_ids);
        }

        $task->load('keywords');

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'data' => $task
        ], 201);
    }

    /**
     * Change the is_done state of the task (true ↔ false).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function toggle(int $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.'
            ], 404);
        }

        $task->update([
            'is_done' => !$task->is_done
        ]);

        $task->load('keywords');

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
            'data' => $task
        ]);
    }
}
