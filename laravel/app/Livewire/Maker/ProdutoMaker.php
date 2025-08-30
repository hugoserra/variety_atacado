<?php

namespace App\Livewire\Maker;

use App\Models\Produto;
use Livewire\Attributes\On;
use Livewire\Component;

class ProdutoMaker extends Component
{
    public $nome;
    public $tipo = 'importado';
    public $tipo_frete = 'pago pelo freteiro';
    public $preco_produto_dolar;
    public $porcentagem_frete;
    public $porcentagem_lucro;
    public $quantidade_estoque = 0;
    
    public $ordem_id;
    public $quantidade_produto_ordem;
    public $pedido_id;
    public $quantidade_produto_pedido;
    public $comissao_vendedor;
    public $link_compras_paraguai;

    #[On('novo-produto')]
    public function maker_show()
    {
        $this->modal('novo-produto')->show();
    }

    #[On('set-produto-ordem-id')]
    public function set_produt_ordem_id($ordem_id)
    {
        $this->ordem_id = $ordem_id;
    }

    #[On('set-produto-pedido-id')]
    public function set_produt_pedido_id($pedido_id)
    {
        $this->pedido_id = $pedido_id;
    }

    public function criar()
    {
        $validated = $this->validate([
            'nome' => 'required',
            'tipo' => 'required',
            'tipo_frete' => 'required',
            'preco_produto_dolar' => 'required|numeric',
            'porcentagem_frete' => 'required|numeric',
            'porcentagem_lucro' => 'required|numeric',
            'quantidade_estoque' => 'required|numeric',
            'link_compras_paraguai' => 'url',
        ]);
        $produto = Produto::create($validated);

        if ($this->ordem_id) 
        {
            $this->validate([
                'quantidade_produto_ordem' => 'required|numeric',
            ]);
            $produto->ordens()->attach($this->ordem_id, [
                'quantidade_produto' => $this->quantidade_produto_ordem,
            ]);
        }

        if ($this->pedido_id) 
        {
            $this->validate([
                'quantidade_produto_pedido' => 'required|numeric',
                'comissao_vendedor' => 'required|numeric',
            ]);
            $produto->pedidos()->attach($this->pedido_id, [
                'quantidade_produto' => $this->quantidade_produto_pedido,
                'comissao_vendedor' => $this->comissao_vendedor,
            ]);
        }

        $this->dispatch('produto-saved');
        $this->modal('novo-produto')->close();
        $this->dispatch('saved-popup', 'Novo Produto Criado!');
    }
    
    public function render()
    {
        return view('livewire.maker.produto-maker');
    }
}
