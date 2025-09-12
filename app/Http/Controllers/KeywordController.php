<?php

namespace App\Http\Controllers;

use App\Http\Application\GetKeywordsUseCase;
use App\Http\Application\GetKeywordUseCase;
use App\Http\Application\CreateKeywordUseCase;
use App\Http\Requests\KeywordRequest;
use Illuminate\Http\JsonResponse;

class KeywordController extends Controller
{
    /**
     * List all keywords.
     *
     * @return JsonResponse
     */
    public function index(GetKeywordsUseCase $getKeywordsUseCase): JsonResponse
    {
        $keywords = $getKeywordsUseCase();

        return response()->json([
            'success' => true,
            'data' => $keywords
        ]);
    }

    /**
     * Create a new keyword.
     *
     * @param KeywordRequest $request
     * @return JsonResponse
     */
    public function store(KeywordRequest $request, CreateKeywordUseCase $createKeywordUseCase): JsonResponse
    {
        $keyword = $createKeywordUseCase($request->name);

        return response()->json([
            'success' => true,
            'message' => 'Keyword created successfully.',
            'data' => $keyword
        ], 201);
    }

    /**
     * Show a specific keyword with its associated tasks.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id, GetKeywordUseCase $getKeywordUseCase): JsonResponse
    {
        $keyword = $getKeywordUseCase($id);

        if (!$keyword) {
            return response()->json([
                'success' => false,
                'message' => 'Keyword not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $keyword
        ]);
    }
}
