<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    // list users with optional search
    public function index(Request $request)
    {
        $q = $request->query('q');
        // include trashed users so admin can restore
        $users = User::withTrashed()
            ->when($q, fn($query) => $query->where('name','like',"%{$q}%")->orWhere('email','like',"%{$q}%"))
            ->orderBy('created_at','desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin.users.index', compact('users','q'));
    }


    // show edit form (role change)
    public function edit(User $user)
    {
        $roles = ['admin','coordinator','staff']; // adjust if you have other roles
        return view('admin.users.edit', compact('user','roles'));
    }

    // update role
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin','coordinator','staff'])],
        ]);

        // prevent admin from demoting themself accidentally
        if ($user->id === auth()->id() && $data['role'] !== 'admin') {
            return back()->with('error','You cannot change your own role to a non-admin role.');
        }

        $user->update(['role' => $data['role']]);

        return redirect()->route('admin.users.index')->with('success','User role updated.');
    }

    // delete user (permanent unless you use SoftDeletes)
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error','You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success','User removed.');
    }

    // optional restore (only if you enabled SoftDeletes on User)
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success','User restored.');
    }
}
