<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PedidoProduto extends Pivot
{
    protected $table = 'pedido_produto';
    protected $fillable = ['pedido_id', 'produto_id', 'quantidade_produto', 'preco_paraguai', 'preco_chegada', 'preco_venda', 'observacao'];

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($pedido_produto) {
        });
        static::deleting(function ($pedido_produto) {
        });
    }
}
