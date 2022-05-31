<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $display_name
 */
class MailList extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'display_name', 'slug', 'description'];

    /**
     * Get the contacts subscribed to this list.
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class)->withTimestamps();
    }

    /**
     * Get all the lists formatted for a select list.
     *
     * @return array<int, string>
     */
    public static function getAllForSelect(): array
    {
        return static::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->mapWithKeys(function (self $list) {
                return [$list->id => $list->name];
            })->toArray();
    }
}
