<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int      $id
 * @property string   $name
 * @property string   $content
 * @property string   $subject
 * @property bool     $activated
 * @property ?Carbon  $activated_at
 * @property MailList $maillist
 * @property Campaign $campaign
 */
class Send extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = ['name', 'content', 'subject', 'mail_list_id', 'campaign_id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activated_at' => 'immutable_datetime',
        ];
    }

    /**
     * Get the campaign that this send is in.
     *
     * @return BelongsTo<Campaign, Send>
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the list assigned to this send.
     *
     * @return BelongsTo<MailList, Send>
     */
    public function mailList(): BelongsTo
    {
        return $this->belongsTo(MailList::class);
    }

    /**
     * Get the per-contact records for this send.
     *
     * @return HasMany<SendRecord>
     */
    public function records(): HasMany
    {
        return $this->hasMany(SendRecord::class);
    }

    /**
     * Get the feeds that are using this send.
     *
     * @return HasMany<RssFeed>
     */
    public function feeds(): HasMany
    {
        return $this->hasMany(RssFeed::class, 'template_send_id');
    }

    /**
     * Check if this send has activated or not.
     */
    public function getActivatedAttribute(): bool
    {
        return !is_null($this->activated_at);
    }
}
