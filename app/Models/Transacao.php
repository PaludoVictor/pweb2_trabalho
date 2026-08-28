<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';

    protected $fillable = [
        'descricao_movimento',
        'valor_transacao',
        'data_competencia',
        'metodo_pagamento',
        'conta_id',
        'categoria_id'
    ];

    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
