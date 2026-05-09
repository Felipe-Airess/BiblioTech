<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
    ];

    public static function nomesDisponiveis()
    {
        if (!Schema::hasTable('categorias')) {
            return collect(Livros::CATEGORIAS);
        }

        $categorias = self::orderBy('nome')->pluck('nome');

        return $categorias->isNotEmpty()
            ? $categorias
            : collect(Livros::CATEGORIAS);
    }
}
