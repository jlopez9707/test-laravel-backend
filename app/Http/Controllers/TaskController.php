<?php

namespace App\Http\Controllers;

use App\Http\Application\GetAllTasksUseCase;
use App\Http\Application\CreateTaskUseCase;
use App\Http\Application\ToggleTaskUseCase;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{

    /**
     * Return all tasks with their keywords.
     *
     * @return JsonResponse
     */
    public function index(GetAllTasksUseCase $getAllTasksUseCase): JsonResponse
    {
        $tasks = $getAllTasksUseCase();

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
    public function store(TaskRequest $request, CreateTaskUseCase $createTaskUseCase): JsonResponse
    {
        $task = $createTaskUseCase(
            $request->title,
            $request->keyword_ids ?? null
        );

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
    public function toggle(int $id, ToggleTaskUseCase $toggleTaskUseCase): JsonResponse
    {
        $task = $toggleTaskUseCase($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully.',
            'data' => $task
        ]);
    }
}
