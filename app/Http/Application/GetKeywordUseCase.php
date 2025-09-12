<?php

namespace App\Http\Application;

use App\Models\Keyword;

class GetKeywordUseCase
{
    /**
     * Get a specific keyword with its associated tasks.
     *
     * @param int $id
     * @return Keyword|null
     */
    public function __invoke(int $id): ?Keyword
    {
        return Keyword::with('tasks')->find($id);
    }
}
