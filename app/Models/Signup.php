<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string email
 * @property MailList maillist
 */
class Signup extends Model
{
    use HasFactory;

    protected $fillable = [];

    /**
     * The lifetime of signup requests in days.
     */
    protected static $lifetime = 7;

    public function maillist(): BelongsTo
    {
        return $this->belongsTo(MailList::class, 'mail_list_id');
    }

    /**
     * Check if this signup has expired.
     */
    public function hasExpired(): bool
    {
        $expireTime = now()->subDays(static::$lifetime);
        return $this->created_at <= $expireTime;
    }

    /**
     * Start a query for expired sign-ups.
     */
    public static function whereExpired(): Builder
    {
        $expireTime = now()->subDays(static::$lifetime);
        return self::query()->where('created_at', '<', $expireTime);
    }

    /**
     * Generate a new unique key to use for a signup.
     */
    public static function generateNewKey(): string
    {
        do {
            $key = Str::random(32);
        } while (static::query()->where('key', '=', $key)->count() > 0);
        return $key;
    }
}
