<?php

namespace App\Jobs;

use App\Models\RssFeed;
use App\Services\MailContentParser;
use App\Services\Rss\RssArticle;
use App\Services\Rss\RssParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReviewRssFeedJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $feed;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RssFeed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->feed->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RssParser $rssParser)
    {
        if (!$this->feed->isPending()) {
            return;
        }

        $articles = $rssParser->getArticles($this->feed->url);
        $articlesToUse = $articles->filter(function (RssArticle $article) {
            return $article->pubDate > ($this->feed->last_reviewed_at ?? $this->feed->created_at)
                && $article->pubDate <= now();
        });

        if ($articlesToUse->isNotEmpty()) {
            $send = $this->feed->templateSend->replicate();
            $send->content = (new MailContentParser($send->content))->parseForRss($articlesToUse);
            $send->activated_at = now();
            $send->save();
            dispatch(new SendActivationJob($send));
        }

        $this->feed->last_reviewed_at = now();
        $this->feed->updateNextReviewDate();
        $this->feed->save();
    }
}
