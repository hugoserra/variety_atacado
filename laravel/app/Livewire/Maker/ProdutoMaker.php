<?php

namespace App\Livewire\Maker;

use App\Models\Produto;
use Livewire\Attributes\On;
use Livewire\Component;

class ProdutoMaker extends Component
{
    public $nome;
    public $pedido_id;
    public $quantidade_produto_pedido;

    #[On('novo-produto')]
    public function maker_show()
    {
        $this->modal('novo-produto')->show();
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
        ]);
        $produto = Produto::create($validated);

        if ($this->pedido_id) 
        {
            $this->validate([
                'quantidade_produto_pedido' => 'required|numeric',
            ]);
            $produto->pedidos()->attach($this->pedido_id, [
                'quantidade_produto' => $this->quantidade_produto_pedido,
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
