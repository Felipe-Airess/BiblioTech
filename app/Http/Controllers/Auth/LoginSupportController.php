<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Membros;
use App\Models\User;
use App\Notifications\PasswordHelpRequested;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginSupportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $membro = Membros::where('email', $data['email'])->first();

        User::whereIn('tipo_usuario', ['gerente', 'bibliotecario'])
            ->get()
            ->each(fn (User $admin) => $admin->notify(new PasswordHelpRequested(
                email: $data['email'],
                message: $data['message'] ?? null,
                membro: $membro,
            )));

        return back()->with('status', 'Pedido enviado para a equipe da biblioteca. Procure o balcão caso precise de atendimento imediato.');
    }
}
