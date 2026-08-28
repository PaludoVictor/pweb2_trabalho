<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conta extends Model
{
    use HasFactory;

    protected $table = 'contas';

    protected $fillable = [
        'nome_instituicao',
        'agencia_numero',
        'numero_conta',
        'saldo_atual'
    ];

    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'conta_id');
    }
}
