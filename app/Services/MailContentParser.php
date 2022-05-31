<?php

namespace App\Services;

use App\Models\SendRecord;
use App\Services\Rss\RssArticle;
use Illuminate\Support\Collection;

class MailContentParser
{
    protected string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * Parse the content to be ready for a send.
     */
    public function parseForSend(SendRecord $record): string
    {
        $this->addOrReplaceUnsubscribe($record);

        return $this->content;
    }

    /**
     * Parse the content and insert the given RSS articles
     * where tagged in the content.
     *
     * @param Collection<int, RssArticle> $articles
     */
    public function parseForRss(Collection $articles): string
    {
        $rssSectionRegex = '/' . $this->tagRegex('rss_loop')
            . '(.*?)' . $this->tagRegex('end_rss_loop') . '/s';
        $matches = [];
        preg_match_all($rssSectionRegex, $this->content, $matches);

        foreach ($matches[1] as $index => $matchContent) {
            $rawContent = $matches[0][$index];
            $newContent = collect($articles)->map(function (RssArticle $article) use ($matchContent) {
                return $this->replaceArticleTags($matchContent, $article);
            })->join('');
            $this->content = str_replace($rawContent, $newContent, $this->content);
        }

        return $this->content;
    }

    /**
     * Replace any found article tags with content from the article itself.
     */
    protected function replaceArticleTags(string $content, RssArticle $article): string
    {
        $content = $this->replaceTag($content, 'rss_article_title', $article->title);
        $content = $this->replaceTag($content, 'rss_article_link', $article->link);
        $content = $this->replaceTag($content, 'rss_article_publish_date', $article->pubDate->format('jS \o\f F, Y'));
        $content = $this->replaceTag($content, 'rss_article_description', $article->description);

        return $content;
    }

    /**
     * Add an unsubscribe link to the email, at the tag if existing.
     */
    protected function addOrReplaceUnsubscribe(SendRecord $record): void
    {
        if (!$this->hasTag($this->content, 'unsubscribe_link')) {
            $this->content .= "\n\n" . 'Unsubscribe: {{unsubscribe_link}}';
        }

        $unsubLink = route('unsubscribe.show', ['sendRecord' => $record]);
        $this->content = $this->replaceTag($this->content, 'unsubscribe_link', $unsubLink);
    }

    /**
     * Check if the content has a specific tag.
     */
    protected function hasTag(string $content, string $tagName): bool
    {
        return preg_match('/' . $this->tagRegex($tagName) . '/', $content);
    }

    /**
     * Replace the tags of the given name with the given replacement text.
     */
    protected function replaceTag(string $content, string $tagName, string $replacement): string
    {
        return preg_replace('/' . $this->tagRegex($tagName) . '/', $replacement, $content);
    }

    /**
     * Get the regex pattern to find a tag of the given name.
     */
    protected function tagRegex(string $tagName): string
    {
        return '{{\s*?' . $tagName . '\s*?}}';
    }
}
