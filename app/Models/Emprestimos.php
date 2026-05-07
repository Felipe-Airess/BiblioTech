<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Livros;
use App\Models\Membros;

class Emprestimos extends Model
{
    use HasFactory;

    public const STATUS_SOLICITADO = 'solicitado';
    public const STATUS_APROVADO = 'aprovado';
    public const STATUS_RETIRADO = 'retirado';
    public const STATUS_EM_USO = 'em_uso';
    public const STATUS_DEVOLUCAO_SOLICITADA = 'devolucao_solicitada';
    public const STATUS_DEVOLVIDO = 'devolvido';
    public const STATUS_ENCERRADO = 'encerrado';
    public const STATUS_REJEITADO = 'rejeitado';

    public const STATUS_ATIVOS = [
        self::STATUS_SOLICITADO,
        self::STATUS_APROVADO,
        self::STATUS_RETIRADO,
        self::STATUS_EM_USO,
        self::STATUS_DEVOLUCAO_SOLICITADA,
    ];

    public const STATUS_EM_ANDAMENTO = [
        self::STATUS_RETIRADO,
        self::STATUS_EM_USO,
        self::STATUS_DEVOLUCAO_SOLICITADA,
    ];

    protected $fillable = [
        'membro_id',
        'livro_id',
        'data_emprestimo',
        'status',
        'data_devolucao_prevista',
        'data_devolucao_real',
        'valor_multa',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_reason',
        'rejected_at',
    ];

    protected $casts = [
        'data_emprestimo'         => 'date',
        'data_devolucao_prevista' => 'date',
        'data_devolucao_real'     => 'date',
        'status'                  => 'string',
        'approved_at'             => 'datetime',
        'rejected_at'             => 'datetime',
        'return_requested_at'     => 'datetime',
    ];

    public function isAtrasado(): bool
    {
        if (!$this->data_devolucao_prevista) {
            return false;
        }

        return in_array($this->status, self::STATUS_EM_ANDAMENTO, true)
            && now()->startOfDay()->greaterThan($this->data_devolucao_prevista);
    }

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