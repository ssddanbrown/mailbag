<?php

namespace App\Http\Requests;

use App\Models\Signup;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    /**
     * Prevent the request if this user has signup up multiple
     * times within the last time period.
     *
     * @return bool
     */
    public function authorize()
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:200'
        ];
    }
}