<?php

namespace App\Livewire\Editer;

use App\Models\OrdemProduto;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Produto;
use Livewire\Attributes\On;
use Livewire\Component;

class ProdutoEditer extends Component
{
    public $id;
    public $nome;
    public $tipo_frete;

    public $pedido_id;
    public $quantidade_produto_pedido;
    public $preco_paraguai_pedido;

    public $updated_pivot_campo;

    #[On('editar-produto')]
    public function editer_show($produto_id)
    {
        $produto = Produto::findOrFail($produto_id);
        $this->fill($produto);
        
        $pedido_produto_pivot = PedidoProduto::where('pedido_id', $this->pedido_id)
                                            ->where('produto_id', $produto_id)
                                            ->select('quantidade_produto', 'preco_paraguai')
                                            ->first();

        if(isset($pedido_produto_pivot))
        {
            $this->quantidade_produto_pedido = $pedido_produto_pivot->quantidade_produto;
            $this->preco_paraguai_pedido = $pedido_produto_pivot->preco_paraguai;
        }

        $this->modal('editar-produto')->show();
    }

    #[On('set-produto-pedido-id')]
    public function set_produt_pedido_id($pedido_id)
    {
        $this->pedido_id = $pedido_id;
    }

    public function editar()
    {
        $validated = $this->validate([
            'nome' => 'required',
            'tipo_frete' => 'required',
        ]);
        
        $produto = Produto::findOrFail($this->id);
        $produto->update($validated);

        if ($this->pedido_id) 
        {
            $this->validate([
                'quantidade_produto_pedido' => 'required|numeric',
                'preco_paraguai_pedido' => 'required|numeric',
            ]);

            $data_to_update = [
                'quantidade_produto' => $this->quantidade_produto_pedido,
                'preco_paraguai' => $this->preco_paraguai_pedido,
            ];

            $produto->pedidos()->updateExistingPivot($this->pedido_id, $data_to_update);
            Pedido::find($this->pedido_id)->save();
            $this->dispatch('pedido-saved');
        }

        $this->dispatch('produto-saved');
        $this->modal('editar-produto')->close();
        $this->dispatch('updated-popup', 'Produto Salvo!');
    }

    public function render()
    {
        return view('livewire.editer.produto-editer');
    }
}
