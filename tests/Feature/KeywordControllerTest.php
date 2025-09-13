<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Keyword;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KeywordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_keywords()
    {
        Keyword::factory()->count(3)->create();

        $this->getJson('/api/keywords')
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [[
                    'id', 'name'
                ]]
            ]);
    }

    public function test_store_creates_keyword()
    {
        $payload = ['name' => 'Important'];

        $this->postJson('/api/keywords', $payload)
            ->assertCreated()
            ->assertJsonFragment(['name' => 'Important']);

        $this->assertDatabaseHas('keywords', ['name' => 'Important']);
    }

    public function test_show_returns_keyword_with_tasks()
    {
        // Crear keyword y tasks relacionadas
        $keyword = Keyword::factory()->create();
        $tasks = Task::factory()->count(2)->create();

        // Relacionar manualmente usando attach
        $keyword->tasks()->attach($tasks->pluck('id')->toArray());

        $this->getJson("/api/keywords/{$keyword->id}")
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id', 'name', 'tasks' => [[
                        'id', 'title', 'is_done'
                    ]]
                ]
            ]);
    }

    public function test_show_returns_404_if_keyword_not_found()
    {
        $this->getJson('/api/keywords/999')
            ->assertNotFound()
            ->assertJson(['success' => false]);
    }
}
