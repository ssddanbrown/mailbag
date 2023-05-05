<?php

namespace App\Services\Rss;

use DateTime;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class RssParser
{
    /**
     * Get articles from the RSS URL.
     *
     * @return ?Collection<int, RssArticle>
     */
    public function getArticles(string $url): ?Collection
    {
        $response = Http::get($url);
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

        /** @var Collection<int, RssArticle> $articles */
        $articles = new Collection();
        foreach ($rss->channel->item as $item) {
            $articles->push(new RssArticle(
                html_entity_decode(strval($item->title ?? '')),
                html_entity_decode(strval($item->link ?? '')),
                html_entity_decode(strval($item->description ?? '')),
                Carbon::createFromFormat(DateTime::RSS, strval($item->pubDate)),
            ));
        }

        return $articles;
    }
}
