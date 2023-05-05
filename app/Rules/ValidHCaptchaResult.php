<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ValidHCaptchaResult implements ValidationRule
{
    /**
     * Determine if the validation rule passes.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!config('services.hcaptcha.active')) {
            return;
        }

        $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'response' => $value,
            'secret'   => config('services.hcaptcha.secretkey'),
            'sitekey'  => config('services.hcaptcha.sitekey'),
        ]);

        $isValid = $response->json('success', false);
        if (!$isValid) {
            $fail('The captcha result did not verify');
        }
    }
}
