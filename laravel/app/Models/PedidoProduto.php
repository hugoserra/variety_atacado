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
        static::saving(function ($pedido_produto) {
            $cliente = $pedido_produto->getCliente();
            $pedido_produto->calculaPrecoChegada($cliente);
            $pedido_produto->calculaPrecoVenda($cliente);
        });
        static::deleting(function ($pedido_produto) {
        });
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function getCliente()
    {
        return $this->pedido->cliente;
    }

    protected function calculaPrecoChegada($cliente)
    {
        $this->preco_chegada = $this->preco_paraguai * (1 + ($cliente->porcentagem_frete / 100));
    }

    protected function calculaPrecoVenda($cliente)
    {
        $this->preco_venda = $this->preco_paraguai * (1 + (($cliente->porcentagem_frete + $cliente->porcentagem_lucro) / 100));
    }
}
