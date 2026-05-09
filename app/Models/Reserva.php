<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    public const STATUS_ATIVA = 'ativa';
    public const STATUS_ATENDIDA = 'atendida';
    public const STATUS_CANCELADA = 'cancelada';

    protected $fillable = [
        'membro_id',
        'livro_id',
        'status',
        'cancelada_em',
    ];

    protected $casts = [
        'cancelada_em' => 'datetime',
    ];

    public function membro()
    {
        return $this->belongsTo(Membros::class, 'membro_id');
    }

    public function livro()
    {
        return $this->belongsTo(Livros::class, 'livro_id');
    }

    public function scopeAtivas($query)
    {
        return $query->where('status', self::STATUS_ATIVA);
    }
}
