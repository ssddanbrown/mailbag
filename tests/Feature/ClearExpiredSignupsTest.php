<?php

namespace Tests\Feature;

use App\Jobs\ScrubSignupsJob;
use App\Models\Signup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ClearExpiredSignupsTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_clears_old_signups()
    {
        $validSignUp = Signup::factory()->create();
        $expiredSignUp = Signup::factory()->create([
            'created_at' => now()->subDays(8),
        ]);

        dispatch_now(new ScrubSignupsJob());

        $this->assertDatabaseHas('signups', ['id' => $validSignUp->id]);
        $this->assertDatabaseMissing('signups', ['id' => $expiredSignUp->id]);
    }

    public function test_command_calls_job()
    {
        Bus::fake();
        Artisan::call('mailbag:scrub-signups');
        Bus::assertDispatchedTimes(ScrubSignupsJob::class, 1);
    }
}
