<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

final class AddUserCommandTest extends TestCase
{
    public function test_create_new_user(): void
    {
        $this->artisan('mailbag:add-user')
            ->expectsQuestion('Please provide a name for the user', 'Barry')
            ->expectsQuestion('Please provide an email address for the user', 'barry@example.com')
            ->expectsQuestion('Now please provide a password', 'testpass')
            ->expectsOutput('User created, You can now login with the email barry@example.com and your provided password.');

        $this->assertDatabaseHas('users', [
            'name'  => 'Barry',
            'email' => 'barry@example.com',
        ]);

        $this->assertTrue(Auth::attempt(['email' => 'barry@example.com', 'password' => 'testpass']));
    }

    public function test_create_new_user_with_empty_detail_returns_error(): void
    {
        $this->artisan('mailbag:add-user')
            ->expectsQuestion('Please provide a name for the user', 'Barry')
            ->expectsQuestion('Please provide an email address for the user', 'barry@example.com')
            ->expectsQuestion('Now please provide a password', '')
            ->expectsOutput('Provided password value is empty')
            ->assertExitCode(1);
    }

    public function test_command_when_email_used(): void
    {
        $user = User::factory()->create(['email' => 'barry@example.com']);
        $this->artisan('mailbag:add-user')
            ->expectsQuestion('Please provide a name for the user', 'Barry 123')
            ->expectsQuestion('Please provide an email address for the user', 'barry@example.com')
            ->expectsQuestion('Now please provide a password', 'testpass')
            ->expectsConfirmation('User with that email already exists, Do you want to update them?', 'yes')
            ->expectsOutput('User created, You can now login with the email barry@example.com and your provided password.');

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'Barry 123',
            'email' => 'barry@example.com',
        ]);
    }

    public function test_command_when_email_used_but_reject_update(): void
    {
        $user = User::factory()->create(['email' => 'barry@example.com']);
        $this->artisan('mailbag:add-user')
            ->expectsQuestion('Please provide a name for the user', 'Barry 123')
            ->expectsQuestion('Please provide an email address for the user', 'barry@example.com')
            ->expectsQuestion('Now please provide a password', 'testpass')
            ->expectsConfirmation('User with that email already exists, Do you want to update them?', 'no')
            ->expectsOutput('Taking no action')
            ->assertExitCode(1);

        $this->assertDatabaseMissing('users', [
            'id'    => $user->id,
            'name'  => 'Barry 123',
            'email' => 'barry@example.com',
        ]);
    }
}
