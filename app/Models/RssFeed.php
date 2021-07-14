<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int      $id
 * @property bool     $active
 * @property string   $url
 * @property Campaign $campaign
 * @property Send     $templateSend
 * @property int      $send_frequency
 * @property int      $target_hour
 * @property Carbon   $last_reviewed_at
 * @property Carbon   $next_review_at
 * @property Carbon   $updated_at
 * @property Carbon   $created_at
 */
class RssFeed extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'active', 'template_send_id', 'send_frequency', 'target_hour'];
    public $dates = ['last_reviewed_at', 'next_review_at'];

    /**
     * Get the campaign that this rss feed sits in.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the send that's used as a template for this rss feed.
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
    public function updateNextReviewDate()
    {
        $this->next_review_at = $this->getNextReviewDate();
    }

    protected function getNextReviewDate(): Carbon
    {
        return $this->last_reviewed_at->clone()
             ->addDays($this->send_frequency)
             ->setHour($this->target_hour)->setMinutes(0);
    }
}
