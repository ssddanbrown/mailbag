<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'unsubscribed'];

    /**
     * Get the lists that this contact is subscribed to.
     */
    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(MailList::class)->withTimestamps();
    }

    /**
     * Unsubscribe a contact in the system.
     */
    public function markUnsubscribed()
    {
        $this->update(['unsubscribed' => true]);
        $this->lists()->detach();
    }

    /**
     * Delete this contact along with any relations.
     */
    public function deleteWithRelations(): void
    {
        $this->lists()->detach();
        $this->delete();
    }
}
