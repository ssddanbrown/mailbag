<?php

namespace App\Http\Controllers;

use App\Models\MailList;
use App\Services\EmailListImporter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ListImportController extends Controller
{
    /**
     * Show the view to import contacts to the list.
     */
    public function show(MailList $list): View
    {
        return view('lists.import', compact('list'));
    }

    /**
     * Run the import of contacts to a list.
     */
    public function import(Request $request, MailList $list): RedirectResponse
    {
        $validated = $this->validate($request, [
            'email_list' => 'string|min:2',
        ]);

        $importer = new EmailListImporter($list);
        $results = $importer->import($validated['email_list']);

        $this->showSuccessMessage("Imported {$results['total']} contacts. {$results['created']} new, {$results['updated']} existing.");

        return redirect()->route('lists.show', compact('list'));
    }
}
