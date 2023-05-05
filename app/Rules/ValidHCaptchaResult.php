<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidHCaptchaResult implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! config('services.hcaptcha.active')) {
            return true;
        }

        $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'response' => $value,
            'secret' => config('services.hcaptcha.secretkey'),
            'sitekey' => config('services.hcaptcha.sitekey'),
        ]);

        return $response->json('success', false);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The captcha result did not verify';
    }
}
