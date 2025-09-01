<?php

namespace App\Livewire\Maker;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ClienteMaker extends Component
{
    public $nome;
    public $telefone;
    public $endereco;
    public $porcentagem_frete = 0;
    public $porcentagem_lucro = 0;

    #[On('novo-cliente')]
    public function maker_show()
    {
        $this->modal('novo-cliente')->show();
    }

    public function criar()
    {
        $validated = $this->validate([
            'nome' => 'required|min:3',
            'telefone' => 'required|min:3',
            'endereco' => 'required|min:3',
            'porcentagem_frete' => 'required|numeric|min:0|max:100',
            'porcentagem_lucro' => 'required|numeric|min:0|max:100',
        ]);
        Cliente::create($validated);
        $this->dispatch('cliente-saved');
        $this->modal('novo-cliente')->close();
        $this->dispatch('saved-popup', 'Novo Cliente Criado!');
    }
    
    public function render()
    {
        return view('livewire.maker.cliente-maker');
    }
}
