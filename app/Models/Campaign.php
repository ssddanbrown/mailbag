<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string name
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
     * Get all the campaigns formatted for a select list
     */
    public static function getAllForSelect(): array
    {
        return static::query()->orderBy('name')->get(['id', 'name'])->mapWithKeys(function($campaign) {
            return [$campaign->id => $campaign->name];
        })->toArray();
    }
}
