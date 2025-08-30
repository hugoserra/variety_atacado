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
