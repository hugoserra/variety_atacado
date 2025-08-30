<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PedidoProduto extends Pivot
{
    protected $table = 'pedido_produto';
    protected $fillable = ['pedido_id', 'produto_id', 'quantidade_produto', 'comissao_vendedor', 'preco_final', 'status_estoque', 'observacao'];

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($pedido_produto) {

            if($pedido_produto->isDirty('quantidade_estoque'))
            $pedido_produto->calcularEstoqueOnChange();
        });
        static::deleting(function ($pedido_produto) {
            $pedido_produto->calcularEstoqueOnDelete();
        });
    }

    public function calcularEstoqueOnChange()
    {
        $produto = Produto::where('id', $this->produto_id)->first();
        $produto->quantidade_estoque += $this->getOriginal('quantidade_produto') - $this->quantidade_produto;
        $produto->save();
    }

    public function calcularEstoqueOnDelete()
    {
        $this->refresh();
        $produto = Produto::where('id', $this->produto_id)->first();
        $produto->quantidade_estoque += $this->quantidade_produto;
        $produto->save();
    }
}
