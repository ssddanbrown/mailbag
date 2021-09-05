<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                 $id
 * @property string              $name
 * @property Collection<Send>    $sends
 * @property Collection<RssFeed> $rssFeeds
 */
class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the sends assigned to this campaign.
     */
    public function sends(): HasMany
    {
        return $this->hasMany(Send::class);
    }

    /**
     * Get the RSS feeds created within this campaign.
     */
    public function rssFeeds(): HasMany
    {
        return $this->hasMany(RssFeed::class);
    }

    /**
     * Get all the campaign sends formatted for a select list.
     *
     * @return array<int, string>
     */
    public function getSendsForSelect(): array
    {
        return $this->sends()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->mapWithKeys(fn (Send $send) => [$send->id => $send->name])
            ->toArray();
    }

    /**
     * Get all the campaigns formatted for a select list.
     *
     * @return array<int, string>
     */
    public static function getAllForSelect(): array
    {
        return static::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->mapWithKeys(fn (Campaign $campaign) => [$campaign->id => $campaign->name])->toArray();
    }
}
