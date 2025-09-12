<?php

namespace App\Http\Controllers;

use App\Http\Requests\KeywordRequest;
use App\Models\Keyword;
use Illuminate\Http\JsonResponse;

class KeywordController extends Controller
{
    /**
     * List all keywords.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $keywords = Keyword::all();

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
    public function store(KeywordRequest $request): JsonResponse
    {
        $keyword = Keyword::create([
            'name' => $request->name
        ]);

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
    public function show(int $id): JsonResponse
    {
        $keyword = Keyword::with('tasks')->find($id);

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
