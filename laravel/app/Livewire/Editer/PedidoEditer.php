<?php

namespace App\Livewire\Editer;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class PedidoEditer extends Component
{
    public $id;
    public $cliente_id = null;
    public $status = 'pendente';
    public $comissao_paga = null;
    public $observacao = null;

    public $clientes = [];
    public $produtos;
    
    public $produto_id;
    public $quantidade_produto_pedido;

    public $recalcular_comissao_total = false;

    public function mount()
    {
        $this->clientes = Cliente::get();
        $this->produtos = Produto::get();
    }

    public function updated($name, $value)
    {
        if($value === 'null')
            $this->$name = null;
    }

    #[On('editar-pedido')]
    public function editer_show($pedido_id)
    {
        $pedido = Pedido::findOrFail($pedido_id);
        $this->fill($pedido);
        $this->dispatch('set-produto-pedido-id', $pedido_id);
        $this->modal('editar-pedido')->show();
    }

    public function editar()
    {
        $this->validate([
            'status' => 'required',
        ]);
        $pedido = Pedido::findOrFail($this->id);

        $data = [
            'cliente_id' => $this->cliente_id,
            'status' => $this->status,
            'comissao_paga' => $this->comissao_paga,
            'observacao' => $this->observacao,
        ];

        $pedido->update($data);

        $this->dispatch('updated-popup', 'Pedido Salvo!');

        if($this->recalcular_comissao_total)
        {
            $pedido->calcularCommissaoTotal(true);
            $pedido->save();
        }

        $this->dispatch('pedido-saved');
        $this->modal('editar-pedido')->close();
    }

    public function vincular_produto_pedido()
    {
        $vinculo_produto_pedido = PedidoProduto::where('produto_id', $this->produto_id)->where('pedido_id', $this->id)->first();
        if($vinculo_produto_pedido)
            return $this->dispatch('updated-popup', 'Produto Já Vinculado!');
        
        $this->validate([
            'produto_id' => 'required|numeric',
            'quantidade_produto_pedido' => 'required|numeric',
        ]);
        $produto = Produto::findOrFail($this->produto_id);
        $produto->pedidos()->attach($this->id, [
            'quantidade_produto' => $this->quantidade_produto_pedido,
        ]);
        Pedido::find($this->id)->save();
        $this->dispatch('produto-saved');
        $this->dispatch('updated-popup', 'Produto Vinculado!');
        $this->dispatch('searchable_select_clear_produto_id');
        $this->quantidade_produto_pedido = null;
    }

    #[On('cliente-saved')]
    public function cliente_saved()
    {
        $this->clientes = Auth::user()->clientes;
        $this->cliente_id = $this->clientes->last()['id'];
    }

    public function render()
    {
        return view('livewire.editer.pedido-editer');
    }
}
