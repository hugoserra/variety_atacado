<?php

namespace App\Livewire\Maker;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Pedido;
use App\Services\DolarAPI;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PedidoMaker extends Component
{
    #[Validate('nullable|numeric')]
    public $cliente_id;
    #[Validate('nullable|numeric')]
    public $fornecedor_id;
    #[Validate('required')]
    public $tipo_frete = 'pago pelo comprador';
    public $cotacao_dolar = null;

    public $clientes = [];
    public $fornecedores = [];
    public $ordens;

    public function mount()
    {
        $this->clientes = Cliente::get();
        $this->fornecedores = Fornecedor::get();
    }

    public function updated($name, $value)
    {
        if ($value === 'null')
            $this->$name = null;
    }

    #[On('novo-pedido')]
    public function maker_show()
    {
        $this->modal('novo-pedido')->show();
    }

    public function set_cotacao_dolar()
    {
        if($this->cotacao_dolar === null)
        $this->cotacao_dolar = DolarAPI::getCotacaoDolarMegaEletronicos();
    }

    public function criar()
    {
        $this->validate();

        $data = [
            'cliente_id' => $this->cliente_id,
            'fornecedor_id' => $this->fornecedor_id,
            'tipo_frete' => $this->tipo_frete,
            'cotacao_dolar' => $this->cotacao_dolar,
        ];
        $pedido = Pedido::create($data);

        $this->modal('novo-pedido')->close();
        $this->dispatch('pedido-saved');
        $this->dispatch('editar-pedido', $pedido->id);
        $this->dispatch('saved-popup', 'Novo Pedido Criado!');
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
    
    public function render()
    {
        return view('livewire.maker.pedido-maker');
    }
}
