<?php

namespace App\Livewire\Table;

use App\Models\Fornecedor;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class FornecedorTable extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    #[Url()]
    public $perPage = 20;


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
    }

    public function setSortBy($sortByField)
    {

        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? 'DESC' : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }

    #[On('fornecedor-saved')]
    public function render()
    {
        return view(
            'livewire.table.fornecedor-table',
            [
                'fornecedors' => Fornecedor::search($this->search)
                    ->orderBy($this->sortBy, $this->sortDir)
                    ->paginate($this->perPage)
            ]
        );
    }
}
