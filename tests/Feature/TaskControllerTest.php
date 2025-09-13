<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Keyword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_tasks_with_keywords()
    {
        $keywords = Keyword::factory()->count(2)->create();

        Task::factory()
            ->count(3)
            ->hasAttached($keywords)
            ->create();

        $this->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [[
                    'id',
                    'title',
                    'is_done',
                    'keywords'
                ]]
            ]);
    }


    public function test_store_creates_task_with_keywords()
    {
        $keywords = Keyword::factory()->count(2)->create();

        $payload = [
            'title' => 'Finish docs',
            'keyword_ids' => $keywords->pluck('id')->toArray()
        ];

        $this->postJson('/api/tasks', $payload)
            ->assertCreated()
            ->assertJsonFragment(['title' => 'Finish docs']);

        $this->assertDatabaseHas('tasks', ['title' => 'Finish docs']);
        $this->assertDatabaseCount('task_keyword', 2);
    }

    public function test_toggle_flips_is_done()
    {
        $task = Task::factory()->create(['is_done' => false]);

        $this->patchJson("/api/tasks/{$task->id}/toggle")
            ->assertOk()
            ->assertJsonFragment(['is_done' => true]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_done' => true
        ]);
    }

    public function test_toggle_returns_404_if_not_found()
    {
        $this->patchJson('/api/tasks/999/toggle')
            ->assertNotFound()
            ->assertJson(['success' => false]);
    }
}
