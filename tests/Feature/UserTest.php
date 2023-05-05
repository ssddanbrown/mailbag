<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

final class UserTest extends TestCase
{
    public function test_users_visible_on_index(): void
    {
        $user = User::factory()->create();

        $response = $this->whileLoggedIn()->get('/users');
        $response->assertStatus(200);
        $response->assertSee($user->email);
    }

    public function test_users_index_paginated_at_100_users(): void
    {
        $users = User::factory()->count(350)->create()->sortBy('name')->values();

        $response = $this->whileLoggedIn()->get('/users');
        $response->assertSee('page=4');
        $response->assertDontSee($users[140]->email);

        $response = $this->get('/users?page=2');
        $response->assertSee($users[140]->email);
    }

    public function test_users_index_can_be_searched(): void
    {
        $users = User::factory()->count(500)->create()->sortBy('name')->values();

        $response = $this->whileLoggedIn()->get('/users');
        $response->assertDontSee($users[105]->email);

        $response = $this->get('/users?search=' . $users[105]->email);
        $response->assertSee("/users/{$users[105]->id}");
        $response->assertSee("value=\"{$users[105]->email}\"", false);
    }

    public function test_user_can_be_edited(): void
    {
        $user = User::factory()->create();

        $response = $this->whileLoggedIn()->get("/users/{$user->id}");
        $response->assertSuccessful();
        $response->assertSee($user->email);
        $response->assertSee('Save');
    }

    public function test_user_can_be_saved(): void
    {
        $user = User::factory()->create();

        $response = $this->whileLoggedIn()->followingRedirects()->put("/users/{$user->id}", [
            'email' => 'updated@example.com',
            'name'  => 'Barry Donald',
        ]);
        $response->assertSee('updated@example.com');
        $response->assertSee('User updated!');
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'email' => 'updated@example.com',
            'name'  => 'Barry Donald',
        ]);
    }

    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $response = $this->whileLoggedIn()->delete("/users/{$user->id}");
        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_new_user_view(): void
    {
        $response = $this->whileLoggedIn()->get('/users/create');
        $response->assertStatus(200);
        $response->assertSee('Create new user');
        $response->assertDontSeeText('Delete');
    }

    public function test_user_create_request(): void
    {
        $response = $this->whileLoggedIn()->followingRedirects()->post('/users', [
            'email'    => 'barry@example.com',
            'password' => 'password1234',
            'name'     => 'Barry Donald',
        ]);

        $response->assertSee('barry@example.com');
        $response->assertSee('User created');
        $this->assertDatabaseHas('users', [
            'email' => 'barry@example.com',
            'name'  => 'Barry Donald',
        ]);
    }
}
