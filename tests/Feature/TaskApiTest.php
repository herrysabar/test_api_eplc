<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $token = 'secret-token-123';

    /**
     * Test login with valid credentials.
     */
    public function test_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'token' => 'secret-token-123'
            ]);
    }

    /**
     * Test login with invalid credentials.
     */
    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    /**
     * Test get tasks without token.
     */
    public function test_get_tasks_without_token(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }

    /**
     * Test get tasks with invalid token.
     */
    public function test_get_tasks_with_invalid_token(): void
    {
        $response = $this->getJson('/api/tasks', [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test get tasks with valid token.
     */
    public function test_get_tasks_with_valid_token(): void
    {
        Task::create([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
        ]);

        $response = $this->getJson('/api/tasks', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data',
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    /**
     * Test get task detail.
     */
    public function test_get_task_detail(): void
    {
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
        ]);

        $response = $this->getJson('/api/tasks/' . $task->id, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task retrieved successfully',
                'data' => [
                    'id' => $task->id,
                    'title' => 'Test Task',
                ],
            ]);
    }

    /**
     * Test get task not found.
     */
    public function test_get_task_not_found(): void
    {
        $response = $this->getJson('/api/tasks/999', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not found'
            ]);
    }

    /**
     * Test create task.
     */
    public function test_create_task(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'New Task',
            'description' => 'New Description',
            'status' => 'pending',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Task created successfully',
                'data' => [
                    'title' => 'New Task',
                    'description' => 'New Description',
                    'status' => 'pending',
                ],
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
        ]);
    }

    /**
     * Test create task without title (validation error).
     */
    public function test_create_task_without_title(): void
    {
        $response = $this->postJson('/api/tasks', [
            'description' => 'No title task',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Validation failed',
            ])
            ->assertJsonStructure([
                'errors' => ['title'],
            ]);
    }

    /**
     * Test create task with invalid status.
     */
    public function test_create_task_with_invalid_status(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => 'Task with invalid status',
            'status' => 'invalid',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Validation failed',
            ]);
    }

    /**
     * Test update task.
     */
    public function test_update_task(): void
    {
        $task = Task::create([
            'title' => 'Original Title',
            'status' => 'pending',
        ]);

        $response = $this->putJson('/api/tasks/' . $task->id, [
            'title' => 'Updated Title',
            'status' => 'done',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task updated successfully',
                'data' => [
                    'title' => 'Updated Title',
                    'status' => 'done',
                ],
            ]);
    }

    /**
     * Test partial update task.
     */
    public function test_partial_update_task(): void
    {
        $task = Task::create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'status' => 'pending',
        ]);

        $response = $this->putJson('/api/tasks/' . $task->id, [
            'status' => 'done',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Original Title',
                    'status' => 'done',
                ],
            ]);
    }

    /**
     * Test update task not found.
     */
    public function test_update_task_not_found(): void
    {
        $response = $this->putJson('/api/tasks/999', [
            'title' => 'Updated Title',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not found'
            ]);
    }

    /**
     * Test delete task.
     */
    public function test_delete_task(): void
    {
        $task = Task::create([
            'title' => 'Task to delete',
            'status' => 'pending',
        ]);

        $response = $this->deleteJson('/api/tasks/' . $task->id, [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Task deleted successfully'
            ]);

        // Check soft delete (record still exists but with deleted_at)
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test delete task not found.
     */
    public function test_delete_task_not_found(): void
    {
        $response = $this->deleteJson('/api/tasks/999', [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not found'
            ]);
    }
}
