<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Membros;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $broker = $this->brokerForEmail($request->string('email')->toString());

        if (! $broker) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __(Password::INVALID_USER)]);
        }

        $status = Password::broker($broker)->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    private function brokerForEmail(string $email): ?string
    {
        $email = Str::lower($email);

        if (User::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return 'users';
        }

        if (Membros::whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return 'membros';
        }

        return null;
    }
}
