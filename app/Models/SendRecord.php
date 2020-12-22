<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property Send send
 * @property Contact contact
 */
class SendRecord extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'send_id', 'key'];

    /**
     * Get the send that this record is for.
     */
    public function send(): BelongsTo
    {
        return $this->belongsTo(Send::class);
    }

    /**
     * Get the contact that this record is for.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Generate a set of new unique keys to use
     */
    public static function generateNewKeys($count = 1): Collection
    {
        $keys = Collection::times($count, function() {
            return ['key' => Str::random(16)];
        })->keyBy('key');

        while ($keys->count() < $count) {
            $newKey = Str::random(16);
            $keys->put($newKey, '');
        }

        return $keys->keys();
    }
}
