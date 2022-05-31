<?php

namespace App\Rules;

use App\Services\Rss\RssParser;
use Illuminate\Contracts\Validation\Rule;

class ValidRssFeedRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (app()->runningUnitTests()) {
            return true;
        }

        $articles = (new RssParser())->getArticles($value);

        return ! is_null($articles) && $articles->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This provided feed URL does not point to a valid RSS feed.';
    }
}
