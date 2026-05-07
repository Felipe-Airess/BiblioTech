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
            'password' => 'required|string|min:8|confirmed',
        ]);

        $numeroCarteirinha = $this->gerarNumeroCarteirinha();

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
            'numero_carteirinha' => $numeroCarteirinha,
            'password' => $request->password,
        ]);

        return redirect()->back()->with('sucesso', 'Membro cadastrado com sucesso! Carteirinha gerada: ' . $numeroCarteirinha);
    }

    private function gerarNumeroCarteirinha(): string
    {
        $maiorNumero = Membros::where('numero_carteirinha', 'like', 'BT-%')
            ->get()
            ->map(function (Membros $membro) {
                return (int) preg_replace('/\D/', '', $membro->numero_carteirinha);
            })
            ->max() ?? 0;

        $proximo = $maiorNumero + 1;

        return 'BT-' . str_pad((string) $proximo, 6, '0', STR_PAD_LEFT);
    }
}
