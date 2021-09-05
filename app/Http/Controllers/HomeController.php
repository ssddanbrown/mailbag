<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        if (Str::startsWith(config('app.home_redirect_url', null), 'http')) {
            return redirect(config('app.home_redirect_url'));
        }

        return redirect()->route('login');
    }
}
