<?php

namespace App\Services\Rss;

use Carbon\Carbon;

class RssArticle
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $link;

    /**
     * @var string
     */
    public $description;

    /**
     * @var Carbon
     */
    public $pubDate;

    /**
     * RssArticle constructor.
     */
    public function __construct(string $title, string $link, string $description, Carbon $pubDate)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->pubDate = $pubDate;
    }
}
