<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property bool $active
 * @property string $url
 * @property Campaign $campaign
 * @property Send $templateSend
 * @property int $send_frequency
 * @property Carbon $last_reviewed_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class RssFeed extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'active', 'template_send_id', 'send_frequency'];
    public $timestamps = ['last_reviewed_at'];

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
}
