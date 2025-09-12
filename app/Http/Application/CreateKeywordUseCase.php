<?php

namespace App\Http\Application;

use App\Models\Keyword;

class CreateKeywordUseCase
{
    /**
     * Create a new keyword.
     *
     * @param string $name
     * @return Keyword
     */
    public function __invoke(string $name): Keyword
    {
        return Keyword::create([
            'name' => $name
        ]);
    }
}
