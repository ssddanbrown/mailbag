<?php

namespace App\Http\Requests;

use App\Models\Signup;
use App\Rules\ValidHCaptchaResult;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    /**
     * Prevent the request if this user has signup up multiple
     * times within the last time period.
     */
    public function authorize(): bool
    {
        $email = $this->request->get('email');
        $twelveHoursAgo = now()->subHours(12);

        $recentAttempts = Signup::query()->where('email', '=', $email)
            ->where('updated_at', '>', $twelveHoursAgo)
            ->sum('attempts');

        return $recentAttempts <= 2;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed[]>
     */
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'email', 'max:200'],
        ];

        if (config('services.hcaptcha.active')) {
            $rules['h-captcha-response'] = ['required', new ValidHCaptchaResult()];
        }

        return $rules;
    }
}
