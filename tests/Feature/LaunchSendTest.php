<?php

namespace Tests\Feature;

use App\Jobs\SendActivationJob;
use App\Mail\SendMail;
use App\Models\Contact;
use App\Models\Send;
use App\Models\SendRecord;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

final class LaunchSendTest extends TestCase
{
    public function test_activation_marks_job_as_activated_and_dispatched_job(): void
    {
        $send = Send::factory()->create(['activated_at' => null]);
        Bus::fake();

        $resp = $this->whileLoggedIn()->post("/sends/{$send->id}/launch");
        $resp->assertRedirect("/sends/{$send->id}");

        $resp = $this->followRedirects($resp);
        $resp->assertSee('Send activated and queued for mailing!');

        $send->refresh();
        $this->assertNotNull($send->activated_at);
        Bus::assertDispatched(SendActivationJob::class);
    }

    public function test_activation_of_an_already_activated_send_shows_warning_and_does_not_activate_again(): void
    {
        $send = Send::factory()->create(['activated_at' => now()]);
        Bus::fake();

        $resp = $this->whileLoggedIn()->post("/sends/{$send->id}/launch");
        $resp->assertRedirect("/sends/{$send->id}");

        $resp = $this->followRedirects($resp);
        $resp->assertDontSee('Send activated and queued for mailing!');
        $resp->assertSee('This send has already been activated!');

        Bus::assertNotDispatched(SendActivationJob::class);
    }

    public function test_send_activation_job_creates_send_records_and_jobs_for_each_person_in_list(): void
    {
        $send = Send::factory()->create();
        $contacts = Contact::factory()->count(100)
            ->hasAttached($send->maillist, [], 'lists')
            ->subscribed()->create();

        Mail::fake();
        (new SendActivationJob($send))->handle();

        $this->assertEquals(100, SendRecord::query()->where('send_id', '=', $send->id)->count());
        $this->assertDatabaseHas('send_records', [
            'send_id'    => $send->id,
            'contact_id' => $contacts->first()->id,
        ]);
        $this->assertNotNull($send->records()->first()->key);
        Mail::assertSent(SendMail::class, 100);

        $this->assertNotNull($send->records()->first()->sent_at);
    }

    public function test_sends_not_sent_to_unsubscribed_people_in_list(): void
    {
        $send = Send::factory()->create();
        $contacts = Contact::factory()->count(100)
            ->hasAttached($send->maillist, [], 'lists')
            ->unsubscribed()->create();

        Mail::fake();
        (new SendActivationJob($send))->handle();

        $this->assertEquals(0, SendRecord::query()->where('send_id', '=', $send->id)->count());
        Mail::assertSent(SendMail::class, 0);
    }

    public function test_mail_sent_with_right_address_subject_and_content(): void
    {
        $mailer = Mail::getFacadeRoot();
        Mail::fake();
        $send = Send::factory()->create([
            'content' => 'custom content',
            'subject' => 'my testing subject',
        ]);
        $contact = Contact::factory()
            ->hasAttached($send->maillist, [], 'lists')
            ->subscribed()->create(['email' => 'tester@example.com']);

        (new SendActivationJob($send))->handle();

        Mail::assertSent(SendMail::class, function (SendMail $mail) use ($mailer) {
            Mail::swap($mailer);
            $mail->build();
            $text = $mail->render();

            return $mail->subject === 'my testing subject'
                && Str::contains($text, 'custom content')
                && $mail->hasTo('tester@example.com');
        });
    }
}
