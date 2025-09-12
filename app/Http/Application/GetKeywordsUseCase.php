<?php

namespace App\Http\Application;

use App\Models\Keyword;

class GetKeywordsUseCase
{
    /**
     * Get all keywords.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function __invoke()
    {
        return Keyword::all();
    }
}
