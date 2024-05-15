<?php

namespace Tests\Unit;

use App\Models\SendRecord;
use App\Services\MailContentParser;
use App\Services\Rss\RssArticle;
use Illuminate\Support\Collection;
use Tests\TestCase;

final class MailContentParserTest extends TestCase
{
    public function test_parse_for_send_adds_unsub_link_at_tag_if_existing(): void
    {
        $record = SendRecord::factory()->create();
        $content = 'ABC {{unsubscribe_link}} DEF';

        $parser = new MailContentParser($content);
        $output = $parser->parseForSend($record);

        $this->assertEquals("ABC http://localhost/unsubscribe/{$record->key} DEF", $output);
    }

    public function test_parse_for_send_adds_unsub_link_at_end_if_no_tag(): void
    {
        $record = SendRecord::factory()->create();
        $content = 'ABC DEF';

        $parser = new MailContentParser($content);
        $output = $parser->parseForSend($record);

        $this->assertEquals("ABC DEF\n\n" . "Unsubscribe: http://localhost/unsubscribe/{$record->key}", $output);
    }

    public function test_parse_for_rss_repeats_block_for_each_article(): void
    {
        $articles = $this->getRssArticles(10);
        $content = '{{rss_loop}}{{rss_article_title}}{{end_rss_loop}}';
        $parser = new MailContentParser($content);
        $output = $parser->parseForRss($articles);

        $expected = $articles->pluck('title')->join('');
        $this->assertEquals($expected, $output);
    }

    public function test_parse_for_rss_includes_all_details(): void
    {
        $articles = $this->getRssArticles(1);
        $content = '{{rss_loop}}
        {{rss_article_title}}
        {{rss_article_description}}
        {{rss_article_link}}
        {{rss_article_publish_date}}
        {{end_rss_loop}}';
        $parser = new MailContentParser($content);
        $output = $parser->parseForRss($articles);

        $this->assertStringContainsString('Item 1', $output);
        $this->assertStringContainsString('Item description 1', $output);
        $this->assertStringContainsString('https://example.com/post/1', $output);
        $this->assertStringContainsString(now()->format('jS \o\f F, Y'), $output);
        $this->assertStringNotContainsString('{{', $output);
    }

    protected function getRssArticles(int $count): Collection
    {
        return Collection::times($count, function ($index) {
            return new RssArticle(
                "Item {$index}",
                "https://example.com/post/{$index}",
                "Item description {$index}",
                now(),
            );
        });
    }
}
