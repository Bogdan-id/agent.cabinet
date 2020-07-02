<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style lang="css">
        @page front-page {
            background: url("{{ storage_path('pdf/page1.png') }}") 100% 100% no-repeat;
            background-image-resize: 6;
            size: auto;
        }

        @page table-page {
            background: url("{{ storage_path('pdf/table-page.png') }}") 100% 100% no-repeat;
            background-image-resize: 6;
            margin-top: 10%;
            margin-bottom: 17%;
            margin-left: 0;
            margin-right: 0;
            size: auto;
        }

        .front-page {
            page: front-page;
            page-break-before: right;
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
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="front-page">
        <div style="z-index: 2; height: 297mm;">
            <div style="position: fixed; padding-top: 59.1mm; margin-left: 135mm; font-size: 1.1em; font-weight: bold; font-family: got_b;">
                {{ (new \Carbon\Carbon())->format('d.m.Y')  }}
            </div>
            <div style="position: absolute; margin-left: -15px; right: 0; margin-top: 30px; bottom: 0;">
                <img src="{{ $image }}" width="30mm" height="35mm"
                     style="width: 30mm; height: 20mm; margin: 0;" />

            </div>
            <div style="position: fixed; margin-top: -25.5mm; margin-left: 110mm; font-size: 0.95em; font-family: got_l">
                {{ $car }}
                <br>
                {{ number_format($price, 0, '.', ' ') }}
                <br>
                {{ $term }}
                <br>
                {{ number_format($prepaid, 0, '.', ' ') }}
                <br>
                {{ number_format($avg, 0, '.', ' ') }}
                <br>
                {{ isset($rest) ? number_format($rest, 0, '.', ' ') : 0 }}
                <br>
                {{ $currency }}
            </div>
        </div>
    </div>

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