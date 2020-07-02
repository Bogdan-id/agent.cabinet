<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style lang="css">
        @page table-page {
            margin-top: 10%;
            margin-bottom: 17%;
            margin-left: 0;
            margin-right: 0;
            size: auto;
        }

        .table-page {
            page: table-page;
            page-break-before: right;
        }
        
        table {
            width: 100%;
            autosize: 1;
            border-collapse: collapse;
        }
         
        thead tr {
            background-color: #f02922;
        }

        thead th,
        tfoot th {
            color: white;
            padding: 8px;
            font-size: 12px;
        }

        table td {
            padding: 3px 8px;
            font-size: 12px;
        }
        
        .text-center {
            text-align: center;
        }

    </style>
</head>
<body>

    <div class="table-page">
        <table>
            <thead>
                <tr>
                    <th width="50px">Місяць</th>
                    <th>Відшкодування частини вартості майна,<br> в т.ч. ПДВ. ГРН</th>
                    <th>Винагорода лізингодавця, ГРН</th>
                    <th>Сума платежу, ГРН</th>
                </tr>
            </thead>
            <tbody>
            @foreach($table as $item)
                <tr style="background-color: {{$loop->even ? 'lightgray' : 'white'}}">
                    <td class="text-center">{{number_format($item['n'], 0, '.', ' ')}}</td>
                    <td class="text-center">{{number_format($item['payment-principal'], 0, '.', ' ')}}</td>
                    <td class="text-center">{{number_format($item['interest'], 0, '.', ' ')}}</td>
                    <td class="text-center">{{number_format($item['payment'], 0, '.', ' ')}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr style="background-color: #f02922;">
                <th >Всього:</th>
                <th class="text-center">{{number_format($agg['payment-principal'], 0, '.', ' ')}}</th>
                <th class="text-center">{{number_format($agg['interest'], 0, '.', ' ')}}</th>
                <th class="text-center">{{number_format($agg['payment'], 0, '.', ' ')}}</th>
            </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>