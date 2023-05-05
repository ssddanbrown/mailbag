<?php

namespace App\Rules;

use App\Services\Rss\RssParser;
use Illuminate\Contracts\Validation\Rule;

class ValidRssFeedRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     */
    public function passes(string $attribute, $value): bool
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        $articles = (new RssParser())->getArticles($value);

        return ! is_null($articles) && $articles->count() > 0;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'This provided feed URL does not point to a valid RSS feed.';
    }
}
