<div style="color: black;">
  <p>Добрий день!</p>
  <p>У додатку Ви знайдете:</p>
  @if($leasingObjectType == 'Легкові та комерційні авто' || $leasingObjectType == 'Вантажні авто')
  <div style="padding-left: 15px;"> - комерційну пропозицію з лізингу автомобiля {{ $mark }} {{ $model }}!</div>
  @endif
  @if($leasingObjectType == 'Автобуси')
  <div style="padding-left: 15px;"> - комерційну пропозицію з лізингу автобуса {{ $mark }} {{ $model }}!</div>
  @endif
  @if($leasingObjectType == 'СПЕЦІАЛЬНІ ТЗ' || $leasingObjectType == 'Сільгосптехніка')
  <div style="padding-left: 15px;"> - комерційну пропозицію з лізингу технiки {{ $mark }} {{ $model }}!</div>
  @endif
  @if($leasingObjectType == 'Обладнання')
  <div style="padding-left: 15px;"> - комерційну пропозицію з лізингу обладнання {{ $model }}!</div>
  @endif
  @if($leasingObjectType == 'Причепи та Напівпричепи')
  <div style="padding-left: 15px;"> - комерційну пропозицію з лізингу причiпа {{ $mark }} {{ $model }}!</div>
  @endif

  <p>Прошу звернути увагу на:</p>
  @if($currency == 'UAH')
    <div style="padding-left: 15px;">- комерційна пропозиція підготовлена у ГРН (Можливо і USD/EURO).</div>
  @endif
  @if($currency == 'USD')
    <div style="padding-left: 15px;">- комерційна пропозиція підготовлена у USD (Можливо і ГРН/EURO).</div>
  @endif
  @if($currency == 'EURO')
    <div style="padding-left: 15px;">- комерційна пропозиція підготовлена у EURO (Можливо і ГРН/USD).</div>
  @endif
  <p>Лізингові платежі включають:</p>

  @if($leasingObjectType == 'Легкові та комерційні авто' || $leasingObjectType == 'Вантажні авто' || $leasingObjectType == 'Автобуси')
    <div style="padding-left: 15px;">- всі витрати з реєстрації авто у Сервісному Центрі</div>
    <div style="padding-left: 15px;">- всі витрати пов’язані зі страхуванням (КАСКО та ЦВ) на весь строк лізингу</div>
    <div style="padding-left: 15px;">- ассистанс (заміна пробитого колеса, запуск двигуна, доставка палива, евакуатор, телефон гарячої лінії…)</div>
    <div style="padding-left: 15px;">- вартість авто</div>
    <div style="padding-left: 15px;">- вартість фінансування</div>
  @endif

  @if($leasingObjectType == 'Автобуси')
    <div style="padding-left: 15px;">- всі витрати з реєстрації автобусу у Сервісному Центрі</div>
    <div style="padding-left: 15px;">- всі витрати пов’язані зі страхуванням (КАСКО та ЦВ) на весь строк лізингу</div>
    <div style="padding-left: 15px;">- ассистанс (заміна пробитого колеса, запуск двигуна, доставка палива, евакуатор, телефон гарячої лінії…)</div>
    <div style="padding-left: 15px;">- вартість автобусу</div>
    <div style="padding-left: 15px;">- вартість фінансування</div>
  @endif

  @if($leasingObjectType == 'Причепи та Напівпричепи')
    <div style="padding-left: 15px;">- всі витрати з реєстрації причіпа у Сервісному Центрі</div>
    <div style="padding-left: 15px;">- всі витрати пов’язані зі страхуванням (КАСКО та ЦВ) на весь строк лізингу</div>
    <div style="padding-left: 15px;">- ассистанс (заміна пробитого колеса, евакуатор, телефон горячої лінії…)</div>
    <div style="padding-left: 15px;">- вартість причіпа</div>
    <div style="padding-left: 15px;">- вартість фінансування</div>
  @endif

  @if($leasingObjectType == 'СПЕЦІАЛЬНІ ТЗ' || $leasingObjectType == 'Сільгосптехніка')
    <div style="padding-left: 15px;">- витрати з реєстрації техніки</div>
    <div style="padding-left: 15px;">- всі витрати пов’язані зі страхуванням на весь строк лізингу</div>
    <div style="padding-left: 15px;">- ассистанс (заміна пробитого колеса, евакуатор, запуск двигуна, телефон горячої лінії…)</div>
    <div style="padding-left: 15px;">- вартість техніки</div>
    <div style="padding-left: 15px;">- вартість фінансування</div>
  @endif

  @if($leasingObjectType == 'Обладнання')
    <div style="padding-left: 15px;">- всі витрати пов’язані зі страхуванням на весь строк лізингу</div>
    <div style="padding-left: 15px;">- ассистанс</div>
    <div style="padding-left: 15px;">- вартість обладнання</div>
    <div style="padding-left: 15px;">- вартість фінансування</div>
  @endif

  <p>Якщо у Вас виникнуть питання, готовий приїхати на зустріч та обсудити їх.</p>
  <p>З повагою,</p>
  <p>{{ $fio }}</p>
  <p>{{ $phone }}</p>
  <p>{{ $email }}</p>
  <br />
</div>