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
        'tipo_conta',
        'agencia_numero',
        'numero_conta',
        'saldo_atual'
    ];

    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'conta_id');
    }

    public function metas()
    {
        return $this->hasMany(Meta::class, 'conta_id');
    }

    public function cartoes()
    {
        return $this->hasMany(Cartao::class, 'conta_id');
    }

    // Relacionamento 1:1 explicito
    public function cartaoPrincipal()
    {
        return $this->hasOne(Cartao::class, 'conta_id');
    }
}
