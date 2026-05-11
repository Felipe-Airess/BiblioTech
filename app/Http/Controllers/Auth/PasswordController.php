<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $guard = Auth::guard('web')->check() ? 'web' : 'membro';

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', "current_password:{$guard}"],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.required' => 'Informe sua senha atual.',
            'current_password.current_password' => 'A senha atual está incorreta.',
            'password.required' => 'Informe a nova senha.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ], [
            'current_password' => 'senha atual',
            'password' => 'nova senha',
            'password_confirmation' => 'confirmação da senha',
        ]);

        $user = Auth::guard($guard)->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('sucesso', 'Senha atualizada com sucesso.');
    }
}
