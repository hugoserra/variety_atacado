<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PedidoProduto extends Pivot
{
    protected $table = 'pedido_produto';
    protected $fillable = ['pedido_id', 'produto_id', 'quantidade_produto', 'preco_paraguai_dolar', 'preco_paraguai', 'preco_chegada', 'preco_venda', 'observacao', 'porcentagem_frete', 'porcentagem_lucro'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($pedido_produto) {
            $pedido_produto->setPorcentagens();
            $pedido_produto->calculaPrecoParaguai();
            $pedido_produto->calculaPrecoChegada();
            $pedido_produto->calculaPrecoVenda();
        });
        static::saved(function ($pedido_produto) {
            $pedido_produto->pedido->calcularTransacao();
        });
        static::deleted(function ($pedido_produto) {
            $pedido_produto->pedido->calcularTransacao();
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

    protected function setPorcentagens()
    {
        if($this->porcentagem_frete == null && $this->porcentagem_lucro == null)
        {
            $cliente = $this->getCliente();
            $this->porcentagem_frete = $cliente->porcentagem_frete;
            $this->porcentagem_lucro = $cliente->porcentagem_lucro;
        }   
    }

    protected function calculaPrecoParaguai()
    {
        $this->preco_paraguai = $this->preco_paraguai_dolar * $this->pedido->cotacao_dolar;
    }

    protected function calculaPrecoChegada()
    {
        $this->preco_chegada = $this->preco_paraguai * (1 + ($this->porcentagem_frete / 100));
    }

    protected function calculaPrecoVenda()
    {
        $this->preco_venda = $this->preco_paraguai * (1 + (($this->porcentagem_frete + $this->porcentagem_lucro) / 100));
    }
}
