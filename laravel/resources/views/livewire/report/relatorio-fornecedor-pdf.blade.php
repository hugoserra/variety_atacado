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
        $total_geral = 0;    
    @endphp
    @foreach ($pedidos as $pedido)
        <div>
            <h2>Pedido #{{$pedido->id}}: {{ $pedido->cliente->nome }} - {{$pedido->created_at->format('d/m/Y')}} </h2>
            <div>
                <h3>Produtos</h3>
                <table>
                    <thead>
                        <th>Nome</th>
                    </thead>
                    <tbody>
                        @foreach ($pedido->produtos as $produto)
                        <tr>
                            <td>{{$produto->nome}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div style="margin-top: 30px;">
            <table>
                <thead>
                    <th>Total Pedido</th>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$pedido->valor_total}}</td>
                        @php
                            $total_geral += $pedido->valor_total;    
                        @endphp
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
    <h2>Total Todos Os Pedidos: R$: {{$total_geral}}</h2>
</body>
</html>