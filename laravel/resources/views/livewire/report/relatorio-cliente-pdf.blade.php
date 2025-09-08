<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            width: 98%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            border: 1px solid black;
            padding-top: 3px;
            padding-bottom: 3px;
            overflow: hidden;
            width: min-content;
            text-overflow:clip;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Relatório de Pedidos - {{ $pedidos[0]->cliente->nome }}</h1>
    @php
        $total_geral = 0;    
    @endphp
    @foreach ($pedidos as $pedido)
        <div>
            <h2>Pedido #{{$pedido->id}} - {{$pedido->created_at->format('d/m/Y')}} </h2>
            <div>
                <h3>Produtos</h3>
                <table>
                    <thead>
                        <th>Nome</th>
                        <th>Qtd.</th>
                        <th>Valor</th>
                    </thead>
                    <tbody>
                        @php
                            $total_a_pagar = 0;
                        @endphp
                        @foreach ($pedido->produtos as $produto)
                        <tr>
                            <td>{{$produto->nome}}</td>
                            <td>{{$produto->pivot->quantidade_produto}}</td>
                            <td>R$ {{number_format($produto->pivot->preco_venda, 2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-top: 30px;">
            <table>
                <thead>
                    <th>Total a Pagar #{{$pedido->id}}</th>
                </thead>
                <tbody>
                        
                    <tr>
                        <td>R$ {{number_format($pedido->preco_total_venda, 2)}}</td>
                        @php
                            $total_geral += $pedido->preco_total_venda;
                        @endphp
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
    <h2>Total Todos Os Pedidos: R$: {{number_format($total_geral, 2)}}</h2>
</body>
</html>