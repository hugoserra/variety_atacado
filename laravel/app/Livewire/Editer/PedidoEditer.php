<?php

namespace App\Livewire\Editer;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Produto;
use App\Services\DolarAPI;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class PedidoEditer extends Component
{
    public $id;
    public $cliente_id = null;
    public $fornecedor_id = null;
    public $status = null;
    public $tipo_frete = null;
    public $cotacao_dolar = null;
    public $observacao = null;

    public $clientes = [];
    public $fornecedores = [];
    public $produtos;
    
    public $produto_id;
    public $quantidade_produto_pedido = 1;
    public $preco_paraguai_dolar_pedido;

    public function mount()
    {
        $this->clientes = Cliente::get();
        $this->fornecedores = Fornecedor::get();
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
            'fornecedor_id' => $this->fornecedor_id,
            'status' => $this->status,
            'tipo_frete' => $this->tipo_frete,
            'observacao' => $this->observacao,
        ];

        $pedido->update($data);

        $this->dispatch('updated-popup', 'Pedido Salvo!');

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
            'preco_paraguai_dolar_pedido' => 'required|numeric',
        ]);
        $produto = Produto::findOrFail($this->produto_id);
        $produto->pedidos()->attach($this->id, [
            'quantidade_produto' => $this->quantidade_produto_pedido,
            'preco_paraguai_dolar' => $this->preco_paraguai_dolar_pedido,
        ]);
        Pedido::find($this->id)->save();
        $this->dispatch('produto-saved');
        $this->dispatch('updated-popup', 'Produto Vinculado!');
        $this->dispatch('searchable_select_clear_produto_id');
        $this->quantidade_produto_pedido = 1;
        $this->preco_paraguai_dolar_pedido = null;
    }

    #[On('cliente-saved')]
    public function cliente_saved()
    {
        $this->clientes = Cliente::get();
        $this->cliente_id = $this->clientes->last()['id'];
    }

    #[On('fornecedor-saved')]
    public function fornecedor_saved()
    {
        $this->fornecedores = Fornecedor::get();
        $this->fornecedor_id = $this->fornecedores->last()['id'];
    }

    public function salvar_cotacao_dolar()
    {
        $pedido = Pedido::findOrFail($this->id);

        if(!$this->cotacao_dolar)
            $this->cotacao_dolar = DolarAPI::getCotacaoDolarMegaEletronicos();

        $pedido->cotacao_dolar = $this->cotacao_dolar;
        $pedido->save();

        $produtos_pedido = PedidoProduto::where('pedido_id', $this->id)->get();
        foreach($produtos_pedido as $produto_pedido)
        {
            $produto_pedido->save();
        }

        $this->dispatch('updated-popup', 'Cotação do Dólar Salva!');
        $this->dispatch('produto-saved');
        $this->dispatch('pedido-saved');

        return $pedido->cotacao_dolar;
    }

    public function salvar_observacao()
    {
        $pedido = Pedido::findOrFail($this->id);

        $pedido->observacao = $this->observacao;

        $pedido->save();
        $this->dispatch('updated-popup', 'Observação Salva!');
        $this->dispatch('pedido-saved');
    }

    public function render()
    {
        return view('livewire.editer.pedido-editer');
    }
}
