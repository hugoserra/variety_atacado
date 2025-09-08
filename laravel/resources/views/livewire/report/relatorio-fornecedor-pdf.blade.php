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
    <h1>Relatório de Pedidos</h1>
    @php
        $ganho_total_geral = 0;    
    @endphp
    @foreach ($pedidos as $pedido)
        @php
            $ganho_total_pedido = 0;    
        @endphp
        <div>
            <h2>Pedido #{{$pedido->id}}: {{ $pedido->cliente->nome }} - {{$pedido->created_at->format('d/m/Y')}} </h2>
            <div>
                <h3>Produtos @if($pedido->tipo_frete == 'pago pelo comprador') Pagos Pelo Comprador @else Pagos Pelo Freteiro @endif</h3>
                <table>
                    <thead>
                        <th>Nome</th>
                        <th>Qtd.</th>
                        <th>Preço Paraguai</th>
                        <th>Preço Chegada</th>
                        <th>Ganho</th>
                    </thead>
                    <tbody>
                        @foreach ($pedido->produtos as $produto)
                        <tr>
                            <td>{{$produto->nome}}</td>
                            <td>{{$produto->pivot->quantidade_produto}}</td>
                            <td>R$ {{number_format($produto->pivot->preco_paraguai, 2)}}</td>
                            <td>R$ {{number_format($produto->pivot->preco_chegada,2)}}</td>
                            @if($pedido->tipo_frete == 'pago pelo comprador')
                                <td>R$ {{number_format($produto->pivot->preco_chegada - $produto->pivot->preco_paraguai,2)}}</td>
                                @else
                                <td>R$ {{number_format($produto->pivot->preco_chegada, 2)}}</td>
                            @endif
                        </tr>
                        @php
                            if($pedido->tipo_frete == 'pago pelo comprador')
                                $ganho_total_pedido += ($produto->pivot->preco_chegada - $produto->pivot->preco_paraguai);
                            else
                                $ganho_total_pedido += $produto->pivot->preco_chegada;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-top: 30px;">
            <table>
                <thead>
                    <th>Ganho Total Pedido</th>
                </thead>
                <tbody>
                    <tr>
                        <td>R$ {{number_format($ganho_total_pedido, 2)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @php
            $ganho_total_geral += $ganho_total_pedido;
        @endphp
    @endforeach
    <h2>Ganho Total Todos Os Pedidos: R$: {{number_format($ganho_total_geral, 2)}}</h2>
</body>
</html>