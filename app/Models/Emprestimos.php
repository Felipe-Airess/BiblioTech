<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Livros;
use App\Models\Membros;

class Emprestimos extends Model
{
    use HasFactory;

    protected $fillable = [
        'membro_id',
        'livro_id',
        'data_emprestimo',
        'status',
        'data_devolucao_prevista',
        'data_devolucao_real',
        'valor_multa',
    ];

    protected $casts = [
        'data_emprestimo'         => 'date',
        'data_devolucao_prevista' => 'date',
        'data_devolucao_real'     => 'date',
        'status'                  => 'string',
    ];

    // Relação 1: O Empréstimo tem um Livro
    public function livro()
    {
        return $this->belongsTo(Livros::class, 'livro_id');
    }

    // FALTAVA ISSO: O Empréstimo pertence a um Membro!
    public function membro()
    {
        return $this->belongsTo(Membros::class, 'membro_id');
    }
}