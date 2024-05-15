<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int             $id
 * @property bool            $active
 * @property string          $url
 * @property Campaign        $campaign
 * @property Send            $templateSend
 * @property int             $send_frequency
 * @property int             $target_hour
 * @property CarbonImmutable $last_reviewed_at
 * @property CarbonImmutable $next_review_at
 * @property Carbon          $updated_at
 * @property Carbon          $created_at
 */
class RssFeed extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = ['url', 'active', 'template_send_id', 'send_frequency', 'target_hour'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_reviewed_at' => 'immutable_datetime',
            'next_review_at'   => 'immutable_datetime',
        ];
    }

    /**
     * Get the campaign that this rss feed sits in.
     *
     * @return BelongsTo<Campaign, RssFeed>
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the send that's used as a template for this rss feed.
     *
     * @return BelongsTo<Send, RssFeed>
     */
    public function templateSend(): BelongsTo
    {
        return $this->belongsTo(Send::class, 'template_send_id');
    }

    /**
     * Check if this feed is pending.
     * Checks the raw data fields instead of any computed fields
     * such as next_review_at.
     */
    public function isPending(): bool
    {
        return boolval($this->active)
            && $this->getNextReviewDate() < now();
    }

    /**
     * Update the time when this feed should be next reviewed.
     */
    public function updateNextReviewDate(): void
    {
        $this->next_review_at = $this->getNextReviewDate();
    }

    protected function getNextReviewDate(): CarbonImmutable
    {
        return $this->last_reviewed_at->clone()
            ->addDays($this->send_frequency)
            ->setHour($this->target_hour)->setMinutes(0);
    }
}
