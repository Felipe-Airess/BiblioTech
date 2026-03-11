<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livros; // Puxando o model de Livro
class LivroController extends Controller
{
    public function create()
    {
        return view('admin.livros.create');
    }
    public function store(Request $request)
    {
        // 1. Validação dos dados e da imagem
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'required|string|unique:livros,isbn', // Garante que não repete ISBN
            'capa' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Só aceita imagem até 2MB
        ]);

        // 2. Prepara os dados básicos
        $dadosLivro = [
            'titulo' => $request->titulo,
            'autor' => $request->autor,
            'isbn' => $request->isbn,
            'e_bestseller' => $request->has('e_bestseller'), // Se marcou o checkbox, vira true (1)
        ];

        // 3. O Pulo do Gato: Salvando a Imagem!
        if ($request->hasFile('capa') && $request->file('capa')->isValid()) {
            // Salva na pasta storage/app/public/capas e guarda o caminho gerado
            $caminhoImagem = $request->capa->store('capas', 'public');
            
            // Adiciona o caminho da imagem na nossa lista de dados para salvar no banco
            $dadosLivro['capa'] = $caminhoImagem;
        }

        // 4. Salva tudo no banco de uma vez só
        Livros::create($dadosLivro);

        return redirect()->back()->with('sucesso', 'Livro cadastrado com sucesso!');
    }
}
