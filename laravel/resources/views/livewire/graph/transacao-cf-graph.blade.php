<div>
    <style>
        .blue {
            background-color: rgba(100,150,254,0.8);
        }
        .red {
            background-color: rgba(239,68,68,0.8);
        }

    </style>
    <div class="flex justify-end mb-3">
        <flux:button x-on:click="$wire.redirect_transacoes()" variant="primary" class="cursor-pointer">Ver Transações</flux:button>
    </div>
    <div class="w-full">
        <canvas id="saldos_clientes" class="w-full h-72"></canvas>
    </div>
    <div class="flex h-8 mb-8 mt-4 items-center space-x-2 text-white bold text-sm">
        <div class="blue p-2 rounded">Pagar</div>
        <div class="red p-2 rounded">Receber</div>
    </div>
    <div class="w-full">
        <canvas id="saldos_fornecedores" class="w-full h-72"></canvas>
    </div>
</div>

@php
    $labels_clientes = [];
    $saldos_clientes = [];
    $colors_clientes = [];

    $saldos_fornecedores = [];
    $labels_fornecedores = [];
    $colors_fornecedores = [];

    foreach ($clientes as $cliente) {
        $labels_clientes[] = $cliente->nome;
        $saldo = $cliente->calcularSaldo();
        $saldos_clientes[] = $saldo;
        $colors_clientes[] = $saldo >= 0 ? 'rgba(100,150,254,0.8)' : 'rgba(239,68,68,0.8)'; // verde ou vermelho
    }
    foreach ($fornecedores as $fornecedor) {
        $labels_fornecedores[] = $fornecedor->nome;
        $saldo = $fornecedor->calcularSaldo();
        $saldos_fornecedores[] = $saldo;
        $colors_fornecedores[] = $saldo >= 0 ? 'rgba(100,150,254,0.8)' : 'rgba(239,68,68,0.8)';
    }
     // Cálculo dos limites simétricos para o eixo Y
    $max_abs_cliente = count($saldos_clientes) ? max(array_map('abs', $saldos_clientes)) : 1;
    $max_abs_fornecedor = count($saldos_fornecedores) ? max(array_map('abs', $saldos_fornecedores)) : 1;
@endphp

<!-- Importa Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function render_saldos_clientes() {
        var canvas_clientes = document.getElementById('saldos_clientes');
        if (!canvas_clientes) return;
       
        var ctx_clientes = canvas_clientes.getContext('2d');
        var data_clientes = {
            labels: @json($labels_clientes),
            datasets: [{
                label: 'Saldo',
                data: @json($saldos_clientes),
                backgroundColor: @json($colors_clientes),
                borderColor: @json($colors_clientes),
                borderWidth: 1,
                borderRadius: 6,
            }]
        };
        var options = {
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
                x: {
                    ticks: {
                        font: {
                            size: 12,
                            weight: 'bold',
                        },
                        color: '#333',
                    }
                },
                y: {
                    min: -{{ $max_abs_cliente }},
                    max: {{ $max_abs_cliente }},
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }
                    },
                    grid: {
                        color: function(ctx) {
                            return 'rgba(0,0,0,0.1)';
                        },
                        lineWidth: function(ctx) {
                            return ctx.tick.value === 0 ? 2 : 1;
                        }
                    }
                }
            }
        };
        new Chart(ctx_clientes, {
            type: 'bar',
            data: data_clientes,
            options: options
        });
    }

    function render_saldos_fornecedores() {
        var canvas_fornecedores = document.getElementById('saldos_fornecedores');
        if (!canvas_fornecedores) return;
      
        var ctx_fornecedores = canvas_fornecedores.getContext('2d');
        var data_fornecedores = {
            labels: @json($labels_fornecedores),
            datasets: [{
                label: 'Saldo',
                data: @json($saldos_fornecedores),
                backgroundColor: @json($colors_fornecedores),
                borderColor: @json($colors_fornecedores),
                borderWidth: 1,
                borderRadius: 6,
            }]
        };
        var options = {
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
                x: {
                    ticks: {
                        font: {
                            size: 12,
                            weight: 'bold',
                        },
                        color: '#333',
                    }
                },
                y: {
                    min: -{{ $max_abs_fornecedor }},
                    max: {{ $max_abs_fornecedor }},
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }
                    },
                    grid: {
                        color: function(ctx) {
                            return 'rgba(0,0,0,0.1)';
                        },
                        lineWidth: function(ctx) {
                            return ctx.tick.value === 0 ? 2 : 1;
                        }
                    }
                }
            }
        };
        new Chart(ctx_fornecedores, {
            type: 'bar',
            data: data_fornecedores,
            options: options
        });
    }

    function renderCharts() {
        render_saldos_clientes();
        render_saldos_fornecedores();
    }

    // Garante renderização ao carregar a página e ao atualizar via Livewire
    document.addEventListener('livewire:load', renderCharts);
    document.addEventListener('livewire:navigated', renderCharts);
    document.addEventListener('DOMContentLoaded', renderCharts);
</script>
