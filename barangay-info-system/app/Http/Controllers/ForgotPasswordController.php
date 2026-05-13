<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Mail\MailException;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email', [
            'loginRouteName' => null,
        ]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::sendResetLink($request->only('email'));

            return back()->with('status', __($status));
        } catch (MailException|Throwable $e) {
            // Common local/dev issue: Mail host (e.g. mailpit) is not reachable,
            // leading to errors like: getaddrinfo for mailpit failed.
            return back()->with('status', __('Email service is currently unavailable. Please try again later.'));
        }
    }
}


