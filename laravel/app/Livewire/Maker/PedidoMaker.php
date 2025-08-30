<?php

namespace App\Livewire\Maker;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PedidoMaker extends Component
{
    #[Validate('nullable|numeric')]
    public $cliente_id;
    #[Validate('required')]
    public $status = 'pendente';

    public $clientes = [];
    public $ordens;

    public function mount()
    {
        $this->clientes = Cliente::get();
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

    public function criar()
    {
        $this->validate();

        $data = [
            'cliente_id' => $this->cliente_id,
            'status' => $this->status,
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
        $this->clientes = Auth::user()->clientes;
        $this->cliente_id = $this->clientes->last()['id'];
    }
    
    public function render()
    {
        return view('livewire.maker.pedido-maker');
    }
}
