<?php

namespace App\Livewire\Maker;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Transacoes;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TransacaoMaker extends Component
{
    public $cliente_id;
    public $clientes = [];
    
    public $fornecedor_id;
    public $fornecedores = [];

    public $descricao;
    public $valor = 0;

    public function mount()
    {
        if(Auth::user()->role == 'admin')
        {
            $this->clientes = Cliente::get();
            $this->fornecedores = Fornecedor::get();
        }
        else
        {
            $cliente = Cliente::where('nome', Auth::user()->name)->first();  
            $fornecedor = Fornecedor::where('nome', Auth::user()->name)->first();  
            if($cliente)
                $this->clientes = [$cliente];
            if($fornecedor)
                $this->fornecedores = [$fornecedor];
            $this->cliente_id = $cliente ? $cliente->id : null;
            $this->fornecedor_id = $fornecedor ? $fornecedor->id : null;
        }
    }

    #[On('nova-transacao')]
    public function maker_show()
    {
        $this->modal('nova-transacao')->show();
    }

    public function criar()
    {
        $validated = $this->validate([
            'cliente_id' => 'nullable',
            'fornecedor_id' => 'nullable',
            'descricao' => 'required',
            'valor' => 'required|numeric',
        ]);
        if (empty($this->cliente_id) && empty($this->fornecedor_id)) {
            $this->addError('cliente_id', 'É obrigatório informar Cliente ou Fornecedor.');
            $this->addError('fornecedor_id', 'É obrigatório informar Cliente ou Fornecedor.');
            return;
        }

        if ($this->cliente_id && $this->fornecedor_id) {
            $this->addError('cliente_id', 'É obrigatório informar Cliente ou Fornecedor, mas não ambos.');
            $this->addError('fornecedor_id', 'É obrigatório informar Cliente ou Fornecedor, mas não ambos.');
            return;
        }

        Transacoes::create($validated);
        $this->dispatch('transacao-saved');
        $this->modal('nova-transacao')->close();
        $this->dispatch('saved-popup', 'Nova Transação Criada!');
    }

    public function render()
    {
        return view('livewire.maker.transacao-maker');
    }
}
