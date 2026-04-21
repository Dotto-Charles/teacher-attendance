<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ VALIDATION
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'check_number'   => 'required|string|max:100|unique:users,check_number',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'phone' => ['nullable', 'regex:/^0[67][0-9]{8}$/', 'unique:users,phone'],
            'sex'            => 'required|in:male,female',
            'password'       => 'required|confirmed|min:6',
        ]);

        // ✅ CREATE USER
        $user = User::create([
            'first_name'   => $request->first_name,
            'middle_name'  => $request->middle_name,
            'last_name'    => $request->last_name,
            'check_number' => $request->check_number,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'sex'          => $request->sex,
            'password'     => Hash::make($request->password),

            // defaults
            'role'   => 'teacher',
            'status' => 'pending',
        ]);

        // ✅ EVENT
        event(new Registered($user));

        // ✅ LOGIN USER
        Auth::login($user);

        // ✅ REDIRECT
        return redirect()->route('dashboard')
            ->with('success', 'Account created. Waiting for approval.');
    }
}