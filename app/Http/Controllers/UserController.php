<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::query()->orderBy('name');
        $search = $request->get('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        $users = $query->paginate(100)->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $default = new User(['unsubscribed' => false]);

        return view('users.create', ['user' => $default]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email'    => 'required|email|unique:users,email',
            'name'     => 'required|string|max:250',
            'password' => 'required|string|min:8|max:250',
        ]);

        $user = new User([
            'email'    => $request->get('email'),
            'name'     => $request->get('name'),
            'password' => Hash::make($request->get('password')),
        ]);
        $user->save();

        $this->showSuccessMessage('User created!');

        return redirect()->route('users.edit', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->validate($request, [
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'name'     => 'required|string|max:250',
            'password' => 'nullable|string|min:8|max:250',
        ]);

        $user->fill($request->only(['email', 'name']));

        if ($request->has('password') && !empty($request->get('password'))) {
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();

        $this->showSuccessMessage('User updated!');

        return redirect()->route('users.edit', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        $this->showSuccessMessage('User deleted!');

        return redirect()->route('users.index');
    }
}
