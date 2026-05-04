<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(Request $request)
{
    $request->validate([
        'check_number' => 'required|string',
        'password' => 'required|string',
    ]);

    $credentials = [
        'check_number' => $request->check_number,
        'password' => $request->password,
    ];

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

             $user = auth()->user();
        return match($user->role) {
    'ward_officer' => redirect()->route('ward.dashboard'),
    'head_teacher' => redirect()->route('dashboard'),
    'teacher'      => redirect()->route('dashboard'),
    default        => redirect('/dashboard'),
};
    }

    return back()->withErrors([
        'check_number' => 'Invalid check number or password.',
    ]);
}

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}