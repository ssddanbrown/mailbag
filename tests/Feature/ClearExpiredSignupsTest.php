<?php

namespace Tests\Feature;

use App\Jobs\ClearExpiredSignupsJob;
use App\Models\Signup;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ClearExpiredSignupsTest extends TestCase
{

    public function test_job_clears_old_signups()
    {
        $validSignUp = Signup::factory()->create();
        $expiredSignUp = Signup::factory()->create([
            'created_at' => now()->subDays(8)
        ]);

        dispatch_now(new ClearExpiredSignupsJob());

        $this->assertDatabaseHas('signups', ['id' => $validSignUp->id]);
        $this->assertDatabaseMissing('signups', ['id' => $expiredSignUp->id]);
    }

    public function test_command_calls_job()
    {
        Bus::fake();
        Artisan::call('mailbag:clear-expired-signups');
        Bus::assertDispatchedTimes(ClearExpiredSignupsJob::class, 1);
    }
}
