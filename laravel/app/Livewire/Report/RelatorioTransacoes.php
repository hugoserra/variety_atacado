<?php

namespace App\Livewire\Report;

use App\Models\Transacoes;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\On;
use Livewire\Component;

class RelatorioTransacoes extends Component
{
    #[On('gerar-relatorio-transacoes')]
    public function gerar_relatorio($transacoes_selecionadas)
    {
        $transacoes = Transacoes::whereIn('id', $transacoes_selecionadas)->orderBy('created_at', 'ASC')->get();

        $pdf = Pdf::loadView('livewire.report.relatorio-transacoes-pdf', ['transacoes' => $transacoes]);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, "RelatórioTransacções[" . implode(',', $transacoes_selecionadas) . "].pdf");
    }
    
    public function render()
    {
        return view('livewire.report.relatorio-transacoes');
    }
}
