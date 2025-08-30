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
    public $tipo;
    public $tipo_frete;
    public $preco_produto_dolar;
    public $porcentagem_frete;
    public $porcentagem_lucro;
    public $quantidade_estoque;
    public $quantidade_produto_pedido;
    public $quantidade_produto_ordem;
    public $preco_final_pedido;
    public $comissao_vendedor;
    public $pedido_id;
    public $ordem_id;
    public $link_compras_paraguai;

    public $updated_pivot_campo;

    #[On('editar-produto')]
    public function editer_show($produto_id)
    {
        $produto = Produto::findOrFail($produto_id);
        $this->fill($produto);
        
        $pedido_produto_pivot = PedidoProduto::where('pedido_id', $this->pedido_id)
                                            ->where('produto_id', $produto_id)
                                            ->select('quantidade_produto', 'comissao_vendedor')
                                            ->first();

        if(isset($pedido_produto_pivot))
        {
            $this->preco_final_pedido = $produto->getPrecoFinalPorPedido($this->pedido_id);
            $this->comissao_vendedor = $produto->getComissaoVendedorPorPedido($this->pedido_id);
            $this->quantidade_produto_pedido = $pedido_produto_pivot->quantidade_produto;
        }

        $ordem_produto_pivot = OrdemProduto::where('ordem_id', $this->ordem_id)
                                            ->where('produto_id', $produto_id)
                                            ->select('quantidade_produto')
                                            ->first();
        if(isset($ordem_produto_pivot))
            $this->quantidade_produto_ordem = $ordem_produto_pivot->quantidade_produto;

        $this->modal('editar-produto')->show();
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

    public function updated($name, $value)
    {
        $produto = Produto::findOrFail($this->id);
        if ($name == 'preco_final_pedido')
        {
            $this->comissao_vendedor = round((float)$value - $produto->preco_venda_minimo, 2);
            $this->updated_pivot_campo = $name;
        }

        if ($name == 'comissao_vendedor')
        {
            $comissao = $value === "" ? $produto->getComissaoVendedorPorPedido($this->pedido_id) : (float)$value;
            $this->preco_final_pedido = round($comissao + $produto->preco_venda_minimo, 2);
            $this->updated_pivot_campo = $name;
        }

    }

    public function editar()
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
        
        $produto = Produto::findOrFail($this->id);
        $produto->update($validated);
        if ($this->ordem_id) 
        {
            $this->validate([
                'quantidade_produto_ordem' => 'required|numeric',
            ]);
            $produto->ordens()->updateExistingPivot($this->ordem_id, [
                'quantidade_produto' => $this->quantidade_produto_ordem,
            ]);
        }

        if ($this->pedido_id) 
        {
            $this->validate([
                'quantidade_produto_pedido' => 'required|numeric',
            ]);

            $data_to_update = [
                'quantidade_produto' => $this->quantidade_produto_pedido,
            ];

            if($this->updated_pivot_campo == "preco_final_pedido")
                $data_to_update['preco_final'] = $this->preco_final_pedido;

            if ($this->updated_pivot_campo == "comissao_vendedor")
            {
                $data_to_update['comissao_vendedor'] = $this->comissao_vendedor;
                $data_to_update['preco_final'] = null;
            }

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
