<?php

namespace App\Livewire\Graph;

use App\Models\Cliente;
use App\Models\Fornecedor;
use Livewire\Component;

class TransacaoCfGraph extends Component
{
    public $clientes = [];
    public $fornecedores = [];

    public function mount()
    {
        $this->clientes = Cliente::get();
        $this->fornecedores = Fornecedor::get();    
    }

    public function redirect_transacoes()
    {
        $this->redirect(route('transacoes'), true);
    }

    public function render()
    {
        return view('livewire.graph.transacao-cf-graph');
    }
}
