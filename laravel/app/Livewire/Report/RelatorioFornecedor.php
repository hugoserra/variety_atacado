<?php

namespace App\Livewire\Report;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\On;
use Livewire\Component;

class RelatorioFornecedor extends Component
{
    #[On('gerar-relatorio-fornecedor')]
    public function gerar_relatorio($pedidos_selecionados)
    {
        if (empty($pedidos_selecionados))
            return $this->dispatch('deleted-popup', 'Escolha pelo menos 1 pedido!', 8000);

        $pedidos = Pedido::withTrashed()->whereIn('id', $pedidos_selecionados)->get();

        $pdf = Pdf::loadView('livewire.report.relatorio-fornecedor-pdf', ['pedidos' => $pedidos]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, "RelatórioFornecedor[" . implode(',', $pedidos_selecionados) . "].pdf");
    }
    
    public function render()
    {
        return view('livewire.report.relatorio-fornecedor');
    }
}
