<?php

namespace App\Livewire\Maker;

use App\Models\Fornecedor;
use Livewire\Attributes\On;
use Livewire\Component;

class FornecedorMaker extends Component
{
    public $nome;
    public $telefone;

    #[On('novo-fornecedor')]
    public function maker_show()
    {
        $this->modal('novo-fornecedor')->show();
    }

    public function criar()
    {
        $validated = $this->validate([
            'nome' => 'required',
            'telefone' => 'required|min:3',
        ]);
        Fornecedor::create($validated);
        $this->dispatch('fornecedor-saved');
        $this->modal('novo-fornecedor')->close();
        $this->dispatch('saved-popup', 'Novo Fornecedor Criado!');
    }

    public function render()
    {
        return view('livewire.maker.fornecedor-maker');
    }
}
