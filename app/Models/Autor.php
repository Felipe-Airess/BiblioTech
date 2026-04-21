<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Autor extends Model
{
    protected $table = 'autores';

    protected $fillable = [
        'nome',
        'foto',
        'biografia',
        'data_nascimento',
        'nacionalidade',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function livros(): HasMany
    {
        return $this->hasMany(Livros::class);
    }
}
