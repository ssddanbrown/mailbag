<?php

namespace App\Http\Controllers;

use App\Models\MailList;
use Illuminate\Http\Request;
use Psy\Util\Str;

class MailListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $query = MailList::query()->orderBy('name')->withCount('contacts');
        $search = $request->get('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('display_name', 'like', '%' . $search . '%');
        }

        $lists = $query->paginate(100)->withQueryString();
        return view('lists.index', [
            'lists' => $lists,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $default = new MailList();
        return view('lists.create', ['list' => $default]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:250',
            'display_name' => 'required|max:250',
            'slug' => 'max:250|alpha_dash|unique:mail_lists,slug',
        ]);

        $list = new MailList($request->all());
        if (empty($list->slug)) {
            $list->slug = \Illuminate\Support\Str::slug($list->display_name);
        }
        $list->save();

        $this->showSuccessMessage('List created!');
        return redirect('lists.edit', compact('list'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MailList $list)
    {
        return view('lists.edit', ['list' => $list]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MailList $list)
    {
        $this->validate($request, [
            'name' => 'required|max:250',
            'display_name' => 'required|max:250',
            'slug' => "max:250|alpha_dash|unique:mail_lists,slug,{$list->id}",
        ]);

        $list->fill($request->all());
        if (empty($list->slug)) {
            $list->slug = \Illuminate\Support\Str::slug($list->display_name);
        }
        $list->save();

        $this->showSuccessMessage('List updated!');
        return redirect('lists.edit', compact('list'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailList $list)
    {
        $list->contacts()->detach();
        $list->delete();
        $this->showSuccessMessage('List deleted!');
        return redirect()->route('lists.index');
    }
}
