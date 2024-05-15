<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    protected function showSuccessMessage(string $message): void
    {
        session()->flash('success', $message);
    }

    protected function showErrorMessage(string $message): void
    {
        session()->flash('error', $message);
    }
}
