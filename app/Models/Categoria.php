<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nome_categoria',
        'descricao',
        'tipo_despesa',
        'limite_orcamento',
        'cor_identificacao'
    ];

    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'categoria_id');
    }

    public function metas()
    {
        return $this->hasMany(Meta::class, 'categoria_id');
    }
}
