<?php

namespace App\Http\Controllers;

use App\Http\Application\GetAllTasksUseCase;
use App\Http\Application\CreateTaskUseCase;
use App\Http\Application\ToggleTaskUseCase;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{

    /**
     * Return all tasks with their keywords.
     *
     * @return JsonResponse
     */
    public function index(GetAllTasksUseCase $getAllTasksUseCase): JsonResponse
    {
        try {
            $tasks = $getAllTasksUseCase();

            return response()->json([
                'success' => true,
                'message' => __('messages.tasks_retrieved_successfully'),
                'data' => $tasks
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving tasks', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.tasks_retrieval_failed'),
                'error' => config('app.debug') ? $e->getMessage() : __('messages.internal_server_error')
            ], 500);
        }
    }

    /**
     * Create a new task and assign existing keywords (by IDs).
     *
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function store(TaskRequest $request, CreateTaskUseCase $createTaskUseCase): JsonResponse
    {
        try {
            $task = $createTaskUseCase(
                $request->title,
                $request->keyword_ids ?? null
            );

            return response()->json([
                'success' => true,
                'message' => __('messages.task_created_successfully'),
                'data' => $task
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating task', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.task_creation_failed'),
                'error' => config('app.debug') ? $e->getMessage() : __('messages.internal_server_error')
            ], 500);
        }
    }

    /**
     * Change the is_done state of the task (true ↔ false).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function toggle(int $id, ToggleTaskUseCase $toggleTaskUseCase): JsonResponse
    {
        try {
            $task = $toggleTaskUseCase($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.task_not_found')
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.task_updated_successfully'),
                'data' => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating task', [
                'error' => $e->getMessage(),
                'task_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.task_update_failed'),
                'error' => config('app.debug') ? $e->getMessage() : __('messages.internal_server_error')
            ], 500);
        }
    }
}
