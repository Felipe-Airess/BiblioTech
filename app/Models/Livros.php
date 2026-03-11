<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Como você usou softDeletes na migration, tem que chamar aqui

class Livros extends Model
{
    use HasFactory, SoftDeletes;

    // Liberando as colunas para serem salvas pelo formulário
    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'e_bestseller',
        'capa',
    ];
}