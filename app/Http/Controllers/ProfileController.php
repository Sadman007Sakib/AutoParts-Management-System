<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users')->ignore($user->id)],
            // new password is optional, but if provided must be confirmed
            'password' => 'nullable|confirmed|min:6',
            // current_password is required for ANY update
            'current_password' => 'required|string',
        ]);

        // verify current password
        if (! Hash::check($data['current_password'], $user->password)) {
            return back()
                ->withInput($request->except(['current_password', 'password', 'password_confirmation']))
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // update basic info
        $user->name = $data['name'];
        $user->email = $data['email'];

        // if password provided, update it
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }




    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        // validate inputs
        $data = $request->validate([
            'current_password' => 'required|string',
            'confirm_email' => 'required|email',
        ]);

        // verify current password
        if (! Hash::check($data['current_password'], $user->password)) {
            return back()
                ->withInput($request->except(['current_password','confirm_email']))
                ->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // verify typed confirmation email matches the user's email
        if ($data['confirm_email'] !== $user->email) {
            return back()
                ->withInput($request->except(['current_password','confirm_email']))
                ->withErrors(['confirm_email' => 'Confirmation email does not match your account email.']);
        }

        // optional: prevent owner/last-admin deletion (if you want)
        // if ($user->role === 'admin' && User::where('role','admin')->count() <= 1) { ... }

        // logout and delete account (soft-delete if you use SoftDeletes, otherwise permanent)
        Auth::logout();

        // if you use session, invalidate it
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect('/')->with('success', 'Your account has been deleted.');
    }
}
