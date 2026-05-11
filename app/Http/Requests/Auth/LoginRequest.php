<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
// Note: not actually used here, kept for reference


class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // tenta primeiro com o guard padrão (web, que cobre o admin/usuários).
        $credentials = $this->only('email','password');
        $remember = $this->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            Auth::guard('membro')->logout();
            RateLimiter::clear($this->throttleKey());
            $this->session()->forget('login_failed_attempts');
            return;
        }

        // se falhar, tentamos também o guard de membros, para que
        // ambos tipos de conta sejam admitidos usando o mesmo formulário.
        if (Auth::guard('membro')->attempt($credentials, $remember)) {
            Auth::guard('web')->logout();
            RateLimiter::clear($this->throttleKey());
            $this->session()->forget('login_failed_attempts');
            return;
        }

        RateLimiter::hit($this->throttleKey());
        $this->session()->increment('login_failed_attempts');

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
