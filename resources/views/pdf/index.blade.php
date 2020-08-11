<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style lang="css">
    .calculation {
      padding: 0 25px;
    }
    .profitable-terms {
      padding: 0 25px;
    }
    .profitable-leasing {
      padding: 0 25px;
    }
    .contacts {
      padding: 0 25px;
      page-break-after: always;
    }
    .graphs {
      padding: 0 25px;
    }
    h4 {
      margin-bottom: 7px!important;
    }
    .custom-header th {
      border: 1px solid black;
    }
    .graph-table thead th {
      padding: 10px;
    }
    .graph-table tfoot th {
      padding: 4px 4px;
    }
    .graph-table td {
      padding: 2px 0;
    }
    body {
      font-family: Helvetica, Verdana, Arial;
    }
    .leasing-object td {
      font-size: 22px;
    }
    .leasing-object-red-devision-table td {
      padding: 0 12px;
    }
    .list ul {
      margin-top: 0;
    }
    ul li {
      font-size: 13px!important;
    }
    .leasing-object-type-background {
      width: 100%; 
      background-size: 100% 100%; 
      min-height: 250px; 
      background-repeat: no-repeat;
    }
  </style>
</head>
<body>
@if($leasingObjType == 'СПЕЦІАЛЬНІ ТЗ')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Specials.png') }}')">
@elseif($leasingObjType == 'Вантажні авто' && $mark != 'ГАЗ')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Truck.png') }}')">
@elseif($leasingObjType == 'Обладнання')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Equipment.png') }}')">
@elseif($leasingObjType == 'Причепи та Напівпричепи')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Trailers.png') }}')">
@elseif($leasingObjType == 'Сільгосптехніка')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Agricultural.png') }}')">
@elseif($mark == 'ГАЗ')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Gaz.png') }}')">
@elseif($mark == 'Renault')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Renault.png') }}')">
@elseif($mark == 'Citroen')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Citroen.png') }}')">
@elseif($mark == 'Nissan')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Nissan.png') }}')">
@elseif($mark == 'Infiniti')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Infiniti.png') }}')">
@elseif($mark == 'Toyota')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Toyota.png') }}')">
@elseif($mark == 'Ravon')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Default.png') }}')">
@elseif($mark == 'Peugeot')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Peugeot.png') }}')">
@elseif($mark == 'Lexus')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Lexus.png') }}')">
@elseif($mark == 'Mercedes-Benz')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Mercedes-Benz.png') }}')">
@elseif($mark == 'Fiat ')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Default.png') }}')">
@elseif($mark == 'Alfa Romeo')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Default.png') }}')">
@elseif($mark == 'Mitsubishi')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Default.png') }}')">
@elseif($mark == 'Opel')
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Default.png') }}')">
@else
  <div class="leasing-object-type-background" style="background: url('{{ storage_path('pdf/Default.png') }}')">
@endif
    <div style="height: 50px; font-size: 15px; font-weight: bold; padding: 20px 35px;">
      <span style="display: inline-block; width: 100%; color: black;">{{ $request_id }}<span>
    </div>
    <div style="height: 100px; color: white; font-size: 42px; font-weight: bold; padding: 7px 15px 28px 20px;">
      <div>БIЛЬШЕ</div>
      <div>НIЖ <img width="35" style="padding: 0 9px 13px 9px" src="{{storage_path('pdf/red-line.png')}}" /></div>
      <div>ЛIЗИНГ</div>
    </div>
  </div>
  <section class="calculation">
    <h4><img width="30" style="padding: 0 9px 3px 0" src="{{storage_path('pdf/red-line.png')}}" />ПОПЕРЕДНІЙ РОЗРАХУНОК З ФІНАНСОВОГО ЛІЗИНГУ ВІД {{ $date }}</h4>
    <table class="leasing-object">
      <tbody>
        <tr>
          <td style="width: 13%;">
            <table style="width: 100%">
              <tbody>
                <tr>
                  <td>
                  <img style="float: left; display: inline-block;" width="155" src="{{ $image }}" />
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
          <td style="width: 87%">
            <table style="width: 100%;">
              <tbody>
                <tr>
                  <td>
                    <table class="leasing-object-red-devision-table" style="width: 100%; border-collapse: collapse;">
                      <tbody>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px; font-family: Helvetica">
                            @if($leasingObjType == 'Легкові та комерційні авто' || $leasingObjType == 'Вантажні авто')
                              АВТОМОБІЛЬ
                            @elseif($leasingObjType == 'СПЕЦІАЛЬНІ ТЗ' || $leasingObjType == 'Сільгосптехніка')
                              ТЕХНIКА
                            @elseif($leasingObjType == 'Обладнання')
                              ОБЛАДНАННЯ
                            @elseif($leasingObjType == 'Причепи та Напівпричепи')
                              ПРИЧIП
                            @endif
                          </td>
                          <td>
                            <table style="width: 200%;">
                              <tr>
                                <td style="padding: 0">{{ $car }}</td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px;">
                            @if($leasingObjType == 'Легкові та комерційні авто' || $leasingObjType == 'Вантажні авто')
                              ВАРТІСТЬ АВТОМОБІЛЯ, ГРН
                            @elseif($leasingObjType == 'СПЕЦІАЛЬНІ ТЗ' || $leasingObjType == 'Сільгосптехніка')
                              ВАРТІСТЬ ТЕХНIКИ, ГРН
                            @elseif($leasingObjType == 'Обладнання')
                              ВАРТІСТЬ ОБЛАДНАННЯ, ГРН
                            @elseif($leasingObjType == 'Причепи та Напівпричепи')
                              ВАРТІСТЬ ПРИЧIПY, ГРН
                            @endif
                          </td>
                          <td align="left" style="font-size: 21px;">{{ number_format($price, 0, '.', ' ') }}</td>
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px;">
                            @if($leasingObjType == 'Легкові та комерційні авто' || $leasingObjType == 'Вантажні авто')
                              ВАРТІСТЬ АВТО З РЕЄСТРАЦІЄЮ, ГРН
                            @elseif($leasingObjType == 'СПЕЦІАЛЬНІ ТЗ' || $leasingObjType == 'Сільгосптехніка')
                              ВАРТІСТЬ ТЕХНIКИ З РЕЄСТРАЦІЄЮ, ГРН
                            @elseif($leasingObjType == 'Обладнання')
                              ВАРТІСТЬ ОБЛАДНАННЯ З РЕЄСТРАЦІЄЮ, ГРН
                            @elseif($leasingObjType == 'Причепи та Напівпричепи')
                              ВАРТІСТЬ ПРИЧIПУ З РЕЄСТРАЦІЄЮ, ГРН
                            @endif
                          </td>
                          <td align="left" style="font-size: 21px;">{{ number_format($offerBrutto, 0, '.', ' ') }}</td>
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px;">ТЕРМІН ЛІЗИНГУ, МІС</td>
                          <td align="left" style="font-size: 21px;">{{ $term }}</td>
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px;">АВАНСОВИЙ ПЛАТІЖ, ГРН</td>
                          <td align="left" style="font-size: 21px;">{{ number_format($prepaid, 0, '.', ' ') }}</td>
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px;">ОДНОРАЗОВА КОМІСІЯ, %</td>
                          <td align="left" style="font-size: 21px;">{{ $oneTimeComission }}</td>
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px;">ГРАФІК ПЛАТЕЖІВ</td>
                          @if(@isset($even))
                            <td align="center" style="border-right: 5px solid #da0303; width: 20%; font-size: 21px;">Класичний</td>
                          @endif
                          @if(@isset($annuity))
                            <td align="center" style="border-right: 5px solid #da0303; width: 20%; font-size: 21px;">Ануїтет</td>
                          @endif
                          @if(@isset($irregular))
                            <td align="center" style="border-right: 5px solid #da0303; width: 20%; font-size: 21px;">Iндивiдуальний</td>
                          @endif
                          @if(@isset($even))
                          @else
                            <td align="center" style="width: 20%;">&nbsp;</td>
                          @endif
                          @if(@isset($annuity))
                          @else
                            <td align="center" style="width: 20%;">&nbsp;</td>
                          @endif
                          @if(@isset($irregular))
                          @else
                            <td align="center" style="width: 20%;">&nbsp;</td>
                          @endif
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; font-size: 21px;">СЕРЕДНЬОМІСЯЧНИЙ ПЛАТІЖ, ГРН</td>
                          @if(@isset($even))
                            <td align="center" style="border-right: 5px solid #da0303; font-size: 21px;">
                              <span>{{ number_format($even['offer-month-payment'], 0, '.', ' ') }}</span>
                            </td>
                          @endif
                          @if(@isset($annuity))
                            <td align="center" style="border-right: 5px solid #da0303; font-size: 21px;">
                              <span>{{ number_format($annuity['offer-month-payment'], 0, '.', ' ') }}</span>
                            </td>
                          @endif
                          @if(@isset($irregular))
                            <td align="center" style="border-right: 5px solid #da0303; font-size: 21px;">
                              <span>{{ number_format($irregular['offer-month-payment'], 0, '.', ' ') }}</span>
                            </td>
                          @endif
                          @if(@isset($even))
                          @else
                            <td align="center" >
                              <span>&nbsp;</span>
                            </td>
                          @endif
                          @if(@isset($annuity))
                          @else
                            <td align="center">
                              <span>&nbsp;</span>
                            </td>
                          @endif
                          @if(@isset($irregular))
                          @else
                            <td align="center">
                              <span>&nbsp;</span>
                            </td>
                          @endif
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; vertical-align: top; font-size: 21px;">ЗАЛИШКОВА ВАРТІСТЬ, ГРН</td>
                          <td align="left" style="font-size: 21px;">{{ isset($rest) ? number_format($rest, 0, '.', ' ') : 0 }}</td>
                        </tr>
                        <tr>
                          <td align="right" style="border-right: 5px solid #da0303; vertical-align: top; font-size: 21px;">ВАЛЮТА ФІНАНСУВАННЯ</td>
                          <td align="left" style="font-size: 21px;">{{ $currency }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </section>
  <section class="profitable-terms">
    <h4><img width="30" style="padding: 0 9px 3px 0" src="{{storage_path('pdf/red-line.png')}}" />ЛІЗИНГОВИЙ ПЛАТІЖ ВЖЕ ВКЛЮЧАЄ:</h4>
    
    <!-- Легковi та Грузовi -->
    @if($leasingObjType == 'Легкові та комерційні авто' || $leasingObjType == 'Вантажні авто')
    <div class="list" style="width: 100%;">
      <div class="profitable-list" style="width: 49%; display: inline-block; float: left">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Вартість нового авто та фінансування</li>
          <li>Додаткове обладнання</li>
          <li>Адміністрування страхових випадків</li>
          <li>Сервісний пакет VAB Сервіс та опції сервісної
            програми VAB Лізинг</li>
        </ul>
      </div>
      <div style="width: 49%; display: inline-block; float: right">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Автомобільний набір в подарунок!</li>
          <li>Страхування КАСКО та ОСЦВП</li>
          <li>Всі витрати на реєстрацію</li>
        </ul>
      </div>
    </div>

    <!-- Технiка -->
    @elseif($leasingObjType == 'СПЕЦІАЛЬНІ ТЗ' || $leasingObjType == 'Сільгосптехніка')
    <div class="list" style="width: 100%;">
      <div class="profitable-list" style="width: 49%; display: inline-block; float: left">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Вартість нової техніки та фінансування</li>
          <li>Додаткове обладнання</li>
          <li>Адміністрування страхових випадків</li>
          <li>Сервісний пакет VAB Сервіс та опції сервісної
            програми VAB Лізинг</li>
        </ul>
      </div>
      <div style="width: 49%; display: inline-block; float: right">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Аварійний набір в подарунок!</li>
          <li>Страхування КАСКО та ОСЦВП</li>
          <li>Всі витрати на реєстрацію</li>
        </ul>
      </div>
    </div>

    <!-- Обладнання -->
    @elseif($leasingObjType == 'Обладнання')
    <div class="list" style="width: 100%;">
      <div class="profitable-list" style="width: 49%; display: inline-block; float: left">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Вартість нового обладнання та фінансування</li>
          <li>Додаткове обладнання</li>
          <li>Адміністрування страхових випадків</li>
        </ul>
      </div>
      <div style="width: 49%; display: inline-block; float: right">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Страхування КАСКО та ОСЦВП</li>
          <li>Всі витрати на реєстрацію</li>
          <li>Сервісний пакет VAB Сервіс та опції сервісної
            програми VAB Лізинг</li>
        </ul>
      </div>
    </div>

    <!-- Причепы -->
    @elseif($leasingObjType == 'Причепи та Напівпричепи')
    <div class="list" style="width: 100%;">
      <div class="profitable-list" style="width: 49%; display: inline-block; float: left">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Вартість нового причіпу та фінансування</li>
          <li>Додаткове обладнання</li>
          <li>Адміністрування страхових випадків</li>
          <li>Сервісний пакет VAB Сервіс та опції сервісної
            програми VAB Лізинг</li>
        </ul>
      </div>
      <div style="width: 49%; display: inline-block; float: right">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Автомобільний набір в подарунок!</li>
          <li>Страхування КАСКО та ОСЦВП</li>
          <li>Всі витрати на реєстрацію</li>
        </ul>
      </div>
    </div>
    @endif
  </section>
  <section class="profitable-leasing">
    <h4><img width="30" style="padding: 0 9px 3px 0" src="{{storage_path('pdf/red-line.png')}}" />ЛІЗИНГ- ЦЕ ВИГІДНО</h4>
    <div class="list" style="width: 100%;">
      <div style="width: 43%; display: inline-block; float: left">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Час прийняття рішення і розгляд документів займає від 2х годин до 7 днів</li>
          <li>Необхідно зібрати не більше 8 документів</li>
          <li>При оформленні потрібно оплатити всього 1 авансовий платіж.</li>
          <li>Лізингова компанія активно бере участь при страхових виплатах.</li>
          <li>Графік погашення складається індивідуально під клієнта</li>
        </ul>
      </div>
      <div style="width: 13%; float: left; height: 175px;">
        <img style="width: 60%; margin: 0 10% 0 30%;" src="{{ storage_path('pdf/leasing-credit.png') }}" />
      </div>
      <div style="width: 43%; display: inline-block; float: right; padding-left: 0;">
        <ul style="list-style-image: url('{{ storage_path('pdf/check.svg') }}')">
          <li>Час на прийняття рішення і розгляд документів може тривати кілька тижнів.</li>
          <li>Необхідно зібрати до 20 документів для оцінки і вдаватися до допомоги нотаріуса</li>
          <li>При оформленні потрібно оплатити до 15 платежів, які складають істотну суму.</li>
          <li>Без допомоги в оформленні, обслуговуванні і страхуванні автомобіля</li>
          <li>Стандартний графік погашення без можливості коригувань</li>
        </ul>
      </div>
    </div>
  </section>
  <section class="contacts">
    <table style="width: 100%; margin-top: 80px;">
      <tr>
        <td style="width: 50%; padding-left: 25px;">
          <div style="display: inline-block;">
            <div style="font-size: 13px">З повагою,</div>
            <div style="font-size: 13px">{{ $agentInfo['name'] }}</div>
            <div style="font-size: 13px">{{ $agentInfo['phone'] }}</div>
            <div style="font-size: 13px">{{ $agentInfo['email'] }}</div>
          </div>
        </td>
        <td style="width: 50%;">
          <img style="width: 130px; margin-left: 200px;" src="data:image/png;base64,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" />
        </td>
      </tr>
    </table>
  </section>
  <section class="graphs">
    <div style="width: 100%; height: 1px; padding-top: 15px;"></div>

    @isset($even)
      @if(count($even) > 0)
        <h4 style="padding-top: 23px">ГРАФІК ЛІЗИНГОВИХ ПЛАТЕЖІВ - Класичний</h4>
        <table class="graph-table" style="border: 1px solid #e0e0e0; width: 882px; border-collapse: collapse;" cellspacing="10">
          <thead>
            <tr style="background-color: #d32f2f">
              <th style="border: 1px solid #e0e0e0; color: white;">П/П</th>
              <th style="border: 1px solid #e0e0e0; color: white;">Погашення основної
                суми заборгованості,
                грн., в т.ч. ПДВ
              </th>
              <th style="border: 1px solid #e0e0e0; color: white;">Винагорода
                лізингодавця по
                Договору, грн.
              </th>
              <th style="border: 1px solid #e0e0e0; color: white;">Сума платежу, ГРН</th>
            </tr>
          </thead>
          <tbody>
          @foreach($even['graph'] as $item)
            <tr>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['n'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['payment-principal'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['interest'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['payment'], 0, '.', ' ')}}</td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">Всього:</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($even['agg']['payment-principal'], 0, '.', ' ')}}</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($even['agg']['interest'], 0, '.', ' ')}}</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($even['agg']['payment'], 0, '.', ' ')}}</th>
            </tr>
          </tfoot>
        </table>
      @endif
    @endisset

    <div style="width: 100%; height: 1px; padding-top: 15px;"></div>

    @isset($annuity)
      @if(count($annuity) > 0)
        <h4 style="padding-top: 23px">ГРАФІК ЛІЗИНГОВИХ ПЛАТЕЖІВ - Ануїтет</h4>
        <table class="graph-table" style="border: 1px solid #e0e0e0; width: 882px; border-collapse: collapse;" cellspacing="10">
          <thead>
            <tr style="background-color: #d32f2f">
              <th style="border: 1px solid #e0e0e0; color: white;">П/П</th>
              <th style="border: 1px solid #e0e0e0; color: white;">Погашення основної
                суми заборгованості,
                грн., в т.ч. ПДВ
              </th>
              <th style="border: 1px solid #e0e0e0; color: white;">Винагорода
                лізингодавця по
                Договору, грн.
              </th>
              <th style="border: 1px solid #e0e0e0; color: white;">Сума платежу, ГРН</th>
            </tr>
          </thead>
          <tbody>
          @foreach($annuity['graph'] as $item)
            <tr>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['n'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['payment-principal'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['interest'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['payment'], 0, '.', ' ')}}</td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">Всього:</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($annuity['agg']['payment-principal'], 0, '.', ' ')}}</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($annuity['agg']['interest'], 0, '.', ' ')}}</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($annuity['agg']['payment'], 0, '.', ' ')}}</th>
            </tr>
          </tfoot>
        </table>
      @endif
    @endisset

    <div style="width: 100%; height: 1px; padding-top: 15px;"></div>

    @isset($irregular)
      @if(count($irregular) > 0)
        <h4 style="padding-top: 23px">ГРАФІК ЛІЗИНГОВИХ ПЛАТЕЖІВ - Iндивiдуальний</h4>
        <table class="graph-table" style="border: 1px solid #e0e0e0; width: 882px; border-collapse: collapse;" cellspacing="10">
          <thead>
            <tr style="background-color: #d32f2f">
              <th style="border: 1px solid #e0e0e0; color: white;">П/П</th>
              <th style="border: 1px solid #e0e0e0; color: white;">Погашення основної
                суми заборгованості,
                грн., в т.ч. ПДВ
              </th>
              <th style="border: 1px solid #e0e0e0; color: white;">Винагорода
                лізингодавця по
                Договору, грн.
              </th>
              <th style="border: 1px solid #e0e0e0; color: white;">Сума платежу, ГРН</th>
            </tr>
          </thead>
          <tbody>
          @foreach($irregular['graph'] as $item)
            <tr>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['n'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['payment-principal'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['interest'], 0, '.', ' ')}}</td>
              <td style="border: 1px solid #e0e0e0;" align="center">{{number_format($item['payment'], 0, '.', ' ')}}</td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">Всього:</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($irregular['agg']['payment-principal'], 0, '.', ' ')}}</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($irregular['agg']['interest'], 0, '.', ' ')}}</th>
              <th style="border: 1px solid #e0e0e0; color: #333333" align="center">{{number_format($irregular['agg']['payment'], 0, '.', ' ')}}</th>
            </tr>
          </tfoot>
        </table>
      @endif
    @endisset
    
  </section>
</body>
</html>