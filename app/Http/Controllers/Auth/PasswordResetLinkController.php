<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OTPMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => __("We can't find a user with that email address."),
        ]);

        $email = $request->email;
        $code = (string) rand(100000, 999999);

        // Save code to database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $code,
                'created_at' => now(),
            ]
        );

        // Send the OTP via email
        Mail::to($email)->send(new OTPMail($code));

        // Save email in session to be verified
        session(['reset_email' => $email]);

        return redirect()->route('password.verify-code-form')
            ->with('status', 'Verification code has been sent to your email.');
    }

    /**
     * Display the verification code entry screen.
     */
    public function showVerifyCodeForm(): View|RedirectResponse
    {
        if (! session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please request a password reset first.']);
        }

        return view('auth.verify-code');
    }

    /**
     * Verify the entered code.
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        if (! session()->has('reset_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please request a password reset first.']);
        }

        $email = session('reset_email');

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $record || $record->token !== $request->code) {
            return back()->withErrors(['code' => 'The code you entered is invalid.']);
        }

        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['code' => 'The verification code has expired. Please request a new one.']);
        }

        session(['reset_verified' => true]);

        return redirect()->route('password.reset');
    }
}
