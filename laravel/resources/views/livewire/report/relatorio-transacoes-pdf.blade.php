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
    <h1>Relatório de Transações  - {{ $transacoes[0]->pessoa()->nome }}</h1>
    @php
        $balanco_geral = 0;    
    @endphp
    <div>
        <div>
            <table>
                <thead>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data</th>
                </thead>
                <tbody>
                    @foreach ($transacoes as $transacao)
                    <tr>
                        <td>#{{$transacao->id}}</td>
                        <td>{{$transacao->descricao}}</td>
                        <td>R$ {{number_format($transacao->valor, 2)}}</td>
                        <td>{{$transacao->created_at}}</td>
                    </tr>
                    @php
                        $balanco_geral += $transacao->valor;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div style="margin-top: 30px;">
        <table>
            <thead>
                <th>Balanço Geral</th>
            </thead>
            <tbody>
                <tr>
                    <td>R$ {{number_format($balanco_geral, 2)}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>