<?php

namespace Tests\Feature;

use Tests\TestCase;

final class HomeTest extends TestCase
{
    public function test_root_url_visit_when_logged_in_redirects_to_dash(): void
    {
        $response = $this->whileLoggedIn()->get('/');
        $response->assertRedirect(route('dashboard'));
    }

    public function test_root_url_visit_when_not_logged_in_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_root_url_visit_when_not_logged_in_with_location_configured_redirects_to_chosen_url(): void
    {
        config()->set('app.home_redirect_url', 'https://example.com/donkey');
        $response = $this->get('/');
        $response->assertRedirect('https://example.com/donkey');
    }
}
