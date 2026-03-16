<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Puxando o model de Usuário
use Illuminate\Support\Facades\Hash; // Para criptografar a senha

class FuncionarioController extends Controller
{
    public function create()
    {
        return view('admin.bibliotecarios.create'); // A tela de cadastro de bibliotecário
    }
    public function store(Request $request)
    {
        // 1. Valida se o gerente preencheu tudo certo
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        // 2. Salva no banco de dados
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Criptografa a senha
            'tipo_usuario' => 'bibliotecario', 
        ]);

        // 3. Devolve para a tela com uma mensagem de sucesso
        return redirect()->back()->with('sucesso', 'Bibliotecário cadastrado com sucesso!');
    }
}