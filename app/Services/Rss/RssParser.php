<?php namespace App\Services\Rss;

use DateTime;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class RssParser
{
    protected $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Get articles from the RSS URL.
     * @returns ?Collection<RssArticle>
     */
    public function getArticles(): ?Collection
    {
        $response = Http::get($this->url);
        if (!$response->ok()) {
            return null;
        }

        libxml_use_internal_errors(true);
        try {
            $rss = new SimpleXMLElement($response->body());
        } catch (Exception $exception) {
            return null;
        }
        $items = $rss->channel->item ?? null;

        if (is_null($items)) {
            return null;
        }

        $articles = collect();
        foreach ($rss->channel->item as $item) {
            $articles->push(new RssArticle(
                strval($item->title) ?? '',
                strval($item->link) ?? '',
                strval($item->description) ?? '',
                Carbon::createFromFormat(DateTime::RSS, strval($item->pubDate)),
            ));
        }

        return $articles;
    }

}
