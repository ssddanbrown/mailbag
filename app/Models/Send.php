<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property string name
 * @property string content
 * @property string subject
 * @property bool activated
 * @property Carbon activated_at
 * @property MailList maillist
 * @property Campaign campaign
 */
class Send extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content', 'subject', 'mail_list_id', 'campaign_id'];
    protected $dates = ['activated_at'];

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
     * Check if this send has activated or not.
     */
    public function getActivatedAttribute(): bool
    {
        return !is_null($this->activated_at);
    }
}
