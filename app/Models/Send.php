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
     * @var string[]
     */
    protected $fillable = ['name', 'content', 'subject', 'mail_list_id', 'campaign_id'];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'activated_at' => 'immutable_datetime',
    ];

    /**
     * Get the campaign that this send is in.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the list assigned to this send.
     */
    public function mailList(): BelongsTo
    {
        return $this->belongsTo(MailList::class);
    }

    /**
     * Get the per-contact records for this send.
     */
    public function records(): HasMany
    {
        return $this->hasMany(SendRecord::class);
    }

    /**
     * Get the feeds that are using this send.
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
