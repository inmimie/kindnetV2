<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (! session()->has('reset_verified') || ! session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please verify your code first.']);
        }

        return view('auth.reset-password');
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (! session()->has('reset_verified') || ! session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please verify your code first.']);
        }

        $email = session('reset_email');

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'User not found.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));

        // Clean up database
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Clean up session
        session()->forget(['reset_email', 'reset_verified']);

        return redirect()->route('login')->with('status', 'Your password has been reset successfully.');
    }
}
