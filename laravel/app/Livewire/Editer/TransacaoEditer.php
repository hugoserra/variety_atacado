<?php

namespace App\Livewire\Editer;

use App\Models\Cliente;
use App\Models\Fornecedor;
use App\Models\Transacoes;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class TransacaoEditer extends Component
{
    public $id;
    public $cliente_id;
    public $clientes = [];

    public $fornecedor_id;
    public $fornecedores = [];

    public $descricao;
    public bool $verificada;
    public $valor = 0;

    public function mount()
    {
        if (Auth::user()->role == 'admin') {
            $this->clientes = Cliente::get();
            $this->fornecedores = Fornecedor::get();
        } else {
            $cliente = Cliente::where('nome', Auth::user()->name)->first();
            $fornecedor = Fornecedor::where('nome', Auth::user()->name)->first();
            if ($cliente)
                $this->clientes = [$cliente];
            if ($fornecedor)
                $this->fornecedores = [$fornecedor];
            $this->cliente_id = $cliente ? $cliente->id : null;
            $this->fornecedor_id = $fornecedor ? $fornecedor->id : null;
        }
    }
    
    #[On('editar-transacao')]
    public function editer_show($transacao_id)
    {
        $transacao = Transacoes::findOrFail($transacao_id);
        $this->fill($transacao);
        $this->modal('editar-transacao')->show();
    }

    public function editar()
    {
        $validated = $this->validate([
            'cliente_id' => 'nullable',
            'fornecedor_id' => 'nullable',
            'descricao' => 'required',
            'verificada' => 'nullable',
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
        
        Transacoes::findOrFail($this->id)->update($validated);
        $this->dispatch('transacao-saved');
        $this->modal('editar-transacao')->close();
        $this->dispatch('updated-popup', 'Transação Salva!');
    }

    public function render()
    {
        return view('livewire.editer.transacao-editer');
    }
}
