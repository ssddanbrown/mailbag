<?php namespace App\Services;

use App\Models\SendRecord;

class MailContentParser
{

    protected $content;

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
     * Add an unsubscribe link to the email, at the tag if existing.
     */
    protected function addOrReplaceUnsubscribe(SendRecord $record)
    {
        if (!$this->hasTag('unsubscribe_link')) {
            $this->content .= "\n\n" . '{{unsubscribe_link}}';
        }

        $unsubLink = route('unsubscribe.show', ['sendRecord' => $record]);
        $this->replaceTag('unsubscribe_link', $unsubLink);
    }

    /**
     * Check if the content has a specific tag.
     */
    protected function hasTag(string $tagName): bool
    {
        return preg_match($this->tagRegex($tagName), $this->content);
    }

    /**
     * Replace the tags of the given name with the given replacement text.
     */
    protected function replaceTag(string $tagName, string $replacement): void
    {
        $this->content = preg_replace($this->tagRegex($tagName), $replacement, $this->content);
    }

    /**
     * Get the regex pattern to find a tag of the given name.
     */
    protected function tagRegex(string $tagName)
    {
        return '/{{\s*?'.$tagName.'\s*?}}/';
    }

}
