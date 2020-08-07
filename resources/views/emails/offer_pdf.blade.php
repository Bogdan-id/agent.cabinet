<p>Добрий день!</p>
<p>У додатку Ви знайдете:</p>
<div style="padding-left: 15px;">- комерційну пропозицію з лізингу 
  {{ $leasingObjectType == 'Легкові та комерційні авто' || $leasingObjectType == 'Вантажні авто' 
    ? 'автомобiля'
    : $leasingObjectType == 'СПЕЦІАЛЬНІ ТЗ' || $leasingObjectType == 'Сільгосптехніка'
      ? 'технiки'
      : $leasingObjectType == 'Обладнання'
        ? 'обладнання'
        : $leasingObjectType == 'Причепи та Напівпричепи'
          ? 'причiпа'
          : ''
  }}
  {{ $model }} {{ $mark }}!</div>
<p>Прошу звернути увагу на:</p>
@if($currency == 'UAH')
  <div style="padding-left: 15px;">- комерційна пропозиція підготовлена у грн (Можливо і USD/EURO).</div>
@endif
@if($currency == 'USD')
  <div style="padding-left: 15px;">- комерційна пропозиція підготовлена у USD (Можливо і UAH/EURO).</div>
@endif
@if($currency == 'EURO')
  <div style="padding-left: 15px;">- комерційна пропозиція підготовлена у EURO (Можливо і UAH/USD).</div>
@endif
<p>Лізингові платежі включають:</p>

@if($leasingObjectType == 'Легкові та комерційні авто' || $leasingObjectType == 'Вантажні авто')
  <div style="padding-left: 15px;">- всі витрати з реєстрації авто у Сервісному Центрі</div>
  <div style="padding-left: 15px;">- всі витрати пов’язані зі страхуванням (КАСКО та ЦВ) на весь строк лізингу</div>
  <div style="padding-left: 15px;">- ассистанс (заміна пробитого колеса, запуск двигуна, доставка палива, евакуатор, телефон гарячої лінії…)</div>
  <div style="padding-left: 15px;">- вартість авто</div>
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
<p>Cпасибі</p>
<p>Контакти агента</p>
<p>{{ $fio }}</p>
<p>{{ $phone }}</p>
<p>{{ $email }}</p>
<br />