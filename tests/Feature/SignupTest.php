<?php

namespace Tests\Feature;

use App\Mail\SignupConfirmationMail;
use App\Models\Contact;
use App\Models\MailList;
use App\Models\Signup;
use Illuminate\Http\Client\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class SignupTest extends TestCase
{
    public function test_signup_page_exists_for_a_list()
    {
        $list = MailList::factory()->create(['display_name' => Str::random(12)]);

        $resp = $this->get("/signup/{$list->slug}");
        $resp->assertSee($list->display_name);
        $resp->assertDontSee($list->name);
        $resp->assertSee($list->description);
    }

    public function test_user_can_sign_up_to_to_list()
    {
        $mailer = Mail::getFacadeRoot();
        Mail::fake();
        $list = MailList::factory()->create();

        $resp = $this->post("/signup/{$list->slug}", [
            'email' => 'tester@example.com',
        ]);
        $resp->assertRedirect("/signup/{$list->slug}/thanks");
        $this->assertDatabaseHas('signups', [
            'email'        => 'tester@example.com',
            'mail_list_id' => $list->id,
            'attempts'     => 1,
        ]);

        $signup = Signup::query()->where('email', '=', 'tester@example.com')->first();
        Mail::assertQueued(SignupConfirmationMail::class, function (Mailable $mail) use ($list, $signup, $mailer) {
            Mail::swap($mailer);
            $mail->render();
            $this->assertStringContainsString($list->display_name, $mail->subject);
            $mail->assertSeeInText($list->display_name);
            $mail->assertSeeInText($signup->key);

            return $mail->hasTo('tester@example.com');
        });
    }

    public function test_more_than_three_signups_to_a_list_will_cause_error()
    {
        Mail::fake();
        $list = MailList::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $resp = $this->post("/signup/{$list->slug}", ['email' => 'tester@example.com']);
            $resp->assertRedirect("/signup/{$list->slug}/thanks");
        }

        for ($i = 0; $i < 2; $i++) {
            $resp = $this->post("/signup/{$list->slug}", ['email' => 'tester@example.com']);
            $resp->assertStatus(403);
        }
    }

    public function test_confirming_a_signup()
    {
        Mail::fake();
        $list = MailList::factory()->create(['display_name' => Str::random()]);
        $this->post("/signup/{$list->slug}", ['email' => 'tester@example.com']);
        $signup = Signup::query()->where('email', '=', 'tester@example.com')->first();

        $resp = $this->get("/signup-confirm/{$signup->key}");
        $resp->assertStatus(200);
        $resp->assertSee($list->display_name);
        $resp->assertSee('Confirm');

        $resp = $this->post("/signup-confirm/{$signup->key}");
        $resp->assertRedirect("/signup-confirm/{$list->slug}/thanks");

        $resp = $this->get("/signup-confirm/{$list->slug}/thanks");
        $resp->assertSee('Thank you');
        $resp->assertSee($list->display_name);

        $this->assertDatabaseHas('contacts', [
            'email' => 'tester@example.com',
        ]);
        $contact = Contact::query()->where('email', '=', 'tester@example.com')->first();
        $this->assertDatabaseHas('contact_mail_list', [
            'contact_id'   => $contact->id,
            'mail_list_id' => $list->id,
        ]);
        $this->assertDatabaseMissing('signups', [
            'email' => 'tester@example.com',
        ]);
    }

    public function test_signup_subscribes_existing_unsubscribed_contact()
    {
        $contact = Contact::factory()->unsubscribed()->create(['email' => 'test@example.com']);
        $list = MailList::factory()->create();
        $this->assertTrue(boolval($contact->unsubscribed));

        $this->post("/signup/{$list->slug}", ['email' => 'test@example.com']);
        $signup = Signup::query()->where('email', '=', 'test@example.com')->first();
        $this->post("/signup-confirm/{$signup->key}");

        $contact->refresh();
        $this->assertFalse(boolval($contact->unsubscribed));
    }

    public function test_signup_confirmation_with_nonexisting_token_shows_nice_message()
    {
        $resp = $this->get('/signup-confirm/abc12345');
        $resp->assertStatus(404);
        $resp->assertSee('Sorry, sign up not found');
    }

    public function test_signup_confirmations_over_a_week_old_shows_not_found_and_deletes_confirmation()
    {
        Mail::fake();
        $list = MailList::factory()->create(['display_name' => Str::random()]);
        $this->post("/signup/{$list->slug}", ['email' => 'tester@example.com']);
        $signup = Signup::query()->where('email', '=', 'tester@example.com')->first();
        $signup->created_at = now()->subDays(8);
        $signup->save();

        $resp = $this->get("/signup-confirm/{$signup->key}");
        $resp->assertStatus(404);
        $resp->assertSee('Sorry, sign up not found');
        $this->assertDatabaseMissing('signups', ['id' => $signup->id]);
    }

    public function test_signup_with_hcaptcha_configured_has_hcaptcha_field_required()
    {
        $list = MailList::factory()->create();
        config()->set('services.hcaptcha.sitekey', 'abc');
        config()->set('services.hcaptcha.secretkey', '123');
        config()->set('services.hcaptcha.active', true);

        $this->post("/signup/{$list->slug}", ['email' => 'test@example.com']);
        $resp = $this->get("/signup/{$list->slug}");
        $resp->assertSee('The h-captcha-response field is required');

        $this->assertDatabaseMissing('signups', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_signup_with_hcaptcha_configured_makes_verification_call()
    {
        $list = MailList::factory()->create();
        config()->set('services.hcaptcha.sitekey', 'abc');
        config()->set('services.hcaptcha.secretkey', '123');
        config()->set('services.hcaptcha.active', true);

        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => true]),
        ]);

        $this->post("/signup/{$list->slug}", [
            'email'              => 'test@example.com',
            'h-captcha-response' => 'def456',
        ]);

        $this->assertDatabaseHas('signups', [
            'email' => 'test@example.com',
        ]);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded')
                && $request->data()['secret'] === '123'
                && $request->data()['response'] === 'def456'
                && $request->data()['sitekey'] === 'abc';
        });
    }
}
