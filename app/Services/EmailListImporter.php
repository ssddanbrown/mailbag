<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\MailList;
use Illuminate\Support\Collection;

class EmailListImporter
{
    protected $list;

    /**
     * EmailListImporter constructor.
     */
    public function __construct(MailList $list)
    {
        $this->list = $list;
    }

    /**
     * Import a newline-separated list of emails as contacts into the
     * system and into the list.
     *
     * @return array{created: int, updated: int, total: int}
     */
    public function import(string $listOfEmails)
    {
        $emails = $this->listToFilteredCollection($listOfEmails);

        $results = $emails->chunk(100)->map(function (Collection $emailChunk) {
            return $this->importChunkOfEmails($emailChunk);
        });

        $created = $results->sum('new');
        $updated = $results->sum('existing');
        $total = $created + $updated;

        return compact('created', 'updated', 'total');
    }

    /**
     * Import the given chunk of email addresses, Adding them to the list
     * and returning the counts of those added, separated by
     * whether they already existed in the database or not.
     */
    protected function importChunkOfEmails(Collection $emailChunk): array
    {
        $existing = Contact::query()->whereIn('email', $emailChunk)->get();
        $existingEmails = $existing->keyBy('email')->map(function ($val) {
            return strtolower($val);
        });

        $toCreateData = $emailChunk->diff($existingEmails->keys())->map(function (string $email) {
            return $this->newContactDataForEmail($email);
        });

        Contact::query()->insert($toCreateData->toArray());
        $newContacts = Contact::query()->whereIn('email', $toCreateData->pluck('email')->toArray());

        $allIds = $existing->pluck('id')->concat($newContacts->pluck('id'));
        $this->list->contacts()->syncWithoutDetaching($allIds);

        return ['new' => count($toCreateData), 'existing' => $existingEmails->count()];
    }

    /**
     * Generate the raw data for a new contact model.
     */
    protected function newContactDataForEmail(string $email)
    {
        return [
            'email'        => $email,
            'created_at'   => now(),
            'updated_at'   => now(),
            'unsubscribed' => false,
        ];
    }

    /**
     * Convert a new-line separated list of emails to a collection
     * of filtered, validated, unique email address strings.
     */
    protected function listToFilteredCollection(string $listOfEmails): Collection
    {
        return collect(explode("\n", $listOfEmails))
            ->map(function ($email) {
                return trim(strtolower($email));
            })->map(function ($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->filter()
            ->unique();
    }
}
