<div class="w-full">
    <canvas id="saldoChart" class="w-full h-96"></canvas>
</div>

@php
    $labels = [];
    $saldos = [];
    $colors = [];
    foreach ($clientes as $cliente) {
        $labels[] = $cliente->nome;
        $saldo = $cliente->calcularSaldo();
        $saldos[] = $saldo;
        $colors[] = $saldo >= 0 ? 'rgba(34,197,94,0.8)' : 'rgba(239,68,68,0.8)'; // verde ou vermelho
    }
    foreach ($fornecedores as $fornecedor) {
        $labels[] = $fornecedor->nome;
        $saldo = $fornecedor->calcularSaldo();
        $saldos[] = $saldo;
        $colors[] = $saldo >= 0 ? 'rgba(34,197,94,0.8)' : 'rgba(239,68,68,0.8)';
    }
@endphp

<!-- Importa Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function renderSaldoChart() {
        const chartId = 'saldoChart';
        const canvas = document.getElementById(chartId);
        if (!canvas) return;
        // Destroi gráfico anterior se existir
        if (window.saldoChartInstance) {
            window.saldoChartInstance.destroy();
        }
        const ctx = canvas.getContext('2d');
        const data = {
            labels: @json($labels),
            datasets: [{
                label: 'Saldo',
                data: @json($saldos),
                backgroundColor: @json($colors),
                borderColor: @json($colors),
                borderWidth: 1,
                borderRadius: 6,
            }]
        };
        const options = {
            responsive: true,
            maintainAspectRatio: false, // importante para respeitar a altura definida no CSS
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += context.parsed.y.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }
                    }
                }
            }
        };
        window.saldoChartInstance = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options
        });
    }

    // Garante renderização ao carregar a página e ao atualizar via Livewire
    document.addEventListener('livewire:load', renderSaldoChart);
    document.addEventListener('livewire:navigated', renderSaldoChart);
    document.addEventListener('DOMContentLoaded', renderSaldoChart);
</script>
