<?php

namespace App\Rules;

use App\Services\Rss\RssParser;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRssFeedRule implements ValidationRule
{
    /**
     * Determine if the validation rule passes.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        $articles = (new RssParser())->getArticles($value);
        $isValid = !is_null($articles) && $articles->count() > 0;

        if (!$isValid) {
            $fail('This provided feed URL does not point to a valid RSS feed.');
        }
    }
}
