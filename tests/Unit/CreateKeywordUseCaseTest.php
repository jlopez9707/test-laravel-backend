<?php

namespace Tests\Unit\Application;

use Tests\TestCase;
use App\Http\Application\CreateKeywordUseCase;
use App\Models\Keyword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateKeywordUseCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_keyword()
    {
        $useCase = new CreateKeywordUseCase();
        $keyword = $useCase('Backend');

        $this->assertInstanceOf(Keyword::class, $keyword);
        $this->assertDatabaseHas('keywords', ['name' => 'Backend']);
    }
}
