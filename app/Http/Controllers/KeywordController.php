<?php

namespace App\Http\Controllers;

use App\Http\Application\GetKeywordsUseCase;
use App\Http\Application\GetKeywordUseCase;
use App\Http\Application\CreateKeywordUseCase;
use App\Http\Requests\KeywordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class KeywordController extends Controller
{
    /**
     * List all keywords.
     *
     * @return JsonResponse
     */
    public function index(GetKeywordsUseCase $getKeywordsUseCase): JsonResponse
    {
        try {
            $keywords = $getKeywordsUseCase();

            return response()->json([
                'success' => true,
                'message' => __('messages.keywords_retrieved_successfully'),
                'data' => $keywords
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving keywords', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.keywords_retrieval_failed'),
                'error' => config('app.debug') ? $e->getMessage() : __('messages.internal_server_error')
            ], 500);
        }
    }

    /**
     * Create a new keyword.
     *
     * @param KeywordRequest $request
     * @return JsonResponse
     */
    public function store(KeywordRequest $request, CreateKeywordUseCase $createKeywordUseCase): JsonResponse
    {
        try {
            $keyword = $createKeywordUseCase($request->name);

            return response()->json([
                'success' => true,
                'message' => __('messages.keyword_created_successfully'),
                'data' => $keyword
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating keyword', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.keyword_creation_failed'),
                'error' => config('app.debug') ? $e->getMessage() : __('messages.internal_server_error')
            ], 500);
        }
    }

    /**
     * Show a specific keyword with its associated tasks.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id, GetKeywordUseCase $getKeywordUseCase): JsonResponse
    {
        try {
            $keyword = $getKeywordUseCase($id);

            if (!$keyword) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.keyword_not_found')
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => __('messages.keyword_retrieved_successfully'),
                'data' => $keyword
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving keyword', [
                'error' => $e->getMessage(),
                'keyword_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.keyword_retrieval_failed'),
                'error' => config('app.debug') ? $e->getMessage() : __('messages.internal_server_error')
            ], 500);
        }
    }
}
