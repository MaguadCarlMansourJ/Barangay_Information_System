<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Default login remains for backward compatibility
        return view('auth.login', [
            'loginRouteName' => 'login',
        ]);
    }

    public function showAdminLoginForm()
    {
        return view('auth.login', [
            'loginRouteName' => 'admin.login.post',
        ]);
    }

    public function showResidentLoginForm()
    {
        return view('auth.login', [
            'loginRouteName' => 'resident.login.post',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Persist last_login_at
            $user = Auth::user();
            \App\Models\User::query()->where('id', $user->id)->update(['last_login_at' => now()]);

            if ($user->role === 'Resident') {
                return redirect()->intended(route('resident-portal.dashboard'));
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        \App\Models\User::query()->where('id', $user->id)->update(['last_login_at' => now()]);

        if ($user->role === 'Resident') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Resident users must log in through the Resident Portal.',
            ]);
        }

        return redirect()->intended('/dashboard');
    }

    public function residentLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        \App\Models\User::query()->where('id', $user->id)->update(['last_login_at' => now()]);

        if ($user->role !== 'Resident') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Admin/Staff users must log in through Admin Login.',
            ]);
        }

        return redirect()->intended(route('resident-portal.dashboard'));
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
