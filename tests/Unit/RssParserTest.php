<?php

namespace Tests\Unit;

use App\Services\Rss\RssParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class RssParserTest extends TestCase
{
    public function test_get_articles_provides_the_rss_articles(): void
    {
        $rss = $this->getRssContent(3);
        Http::fake([
            'https://example.com/feed.xml' => Http::response($rss, 200),
        ]);

        $articles = (new RssParser())->getArticles('https://example.com/feed.xml');
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertEquals(3, $articles->count());
        $this->assertEquals('Recent blog post 1', $articles->first()->title);
    }

    public function test_get_articles_works_when_only_one_article(): void
    {
        $rss = $this->getRssContent(1);
        Http::fake([
            'https://example.com/feed.xml' => Http::response($rss, 200),
        ]);

        $articles = (new RssParser())->getArticles('https://example.com/feed.xml');
        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertEquals(1, $articles->count());
        $this->assertEquals('Recent blog post 1', $articles->first()->title);
    }

    public function test_encoded_entities_are_decoded(): void
    {
        $rss = $this->getRssContent(1);
        $rss = str_replace('Recent blog post 1', 'This &amp;amp; that', $rss);
        Http::fake([
            'https://example.com/feed.xml' => Http::response($rss, 200),
        ]);

        $articles = (new RssParser())->getArticles('https://example.com/feed.xml');
        $this->assertEquals('This & that', $articles->first()->title);
    }

    public function test_get_articles_returns_null_on_request_failure(): void
    {
        Http::fake([
            'https://example.com/feed.xml' => Http::response('', 500),
        ]);

        $articles = (new RssParser())->getArticles('https://example.com/feed.xml');
        $this->assertNull($articles);
    }

    public function test_get_articles_returns_null_on_invalid_xml_content(): void
    {
        Http::fake([
            'https://example.com/feed.xml' => Http::response('<html><p>Hello</p></html>', 200),
        ]);

        $articles = (new RssParser())->getArticles('https://example.com/feed.xml');
        $this->assertNull($articles);
    }

    public function test_get_articles_returns_null_on_non_xml_content(): void
    {
        Http::fake([
            'https://example.com/feed.xml' => Http::response('{"cat": "dog"}', 200),
        ]);

        $articles = (new RssParser())->getArticles('https://example.com/feed.xml');
        $this->assertNull($articles);
    }

    public function getRssContent(int $itemCount): string
    {
        $content = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>Blog Title</title>
    <link>https://example.com/blog/</link>
    <description>Recent content in on the blog</description>';
        for ($i = 1; $i < $itemCount + 1; $i++) {
            $content .= "    <item>
      <title>Recent blog post {$i}</title>
      <link>https://example.com/blog/{$i}/</link>
      <pubDate>Fri, 18 Dec 2020 14:00:00 +0000</pubDate>
      <description>Article {$i} description</description>
    </item>";
        }
        $content .= '  </channel>
</rss>';

        return $content;
    }
}
