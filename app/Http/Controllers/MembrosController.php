<?php

namespace App\Http\Controllers;
use App\Models\Membros;
use Illuminate\Http\Request;
use App\Models\User; // Para criar o usuário associado ao membro
class MembrosController extends Controller
{
    public function create()
    {
        return view('membros.create'); 

    }

    public function store(Request $request)
    {
        // Validação dos dados
        $request->validate([
            
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:membros,email',
            'cpf' => 'required|string|unique:membros,cpf',
            'telefone' => 'required|string|max:20',
            'endereco' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'tipo_membro' => 'required|string|max:50',
            'numero_carteirinha' => 'required|string|unique:membros,numero_carteirinha',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Salvar no banco de dados (senha já será criptografada pelo cast do modelo)
        Membros::create([
            'user_id' => $request->id, // Associa o membro ao usuário criado
            'nome' => $request->nome,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'data_nascimento' => $request->data_nascimento,
            'tipo_membro' => $request->tipo_membro,
            'numero_carteirinha' => $request->numero_carteirinha,
            'password' => $request->password,
        ]);

        return redirect()->back()->with('sucesso', 'Membro cadastrado com sucesso!');
    }
}
