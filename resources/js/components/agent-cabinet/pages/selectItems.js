export default {
  itemConditions: [
    {text: 'Новий', value: true},
    {text: 'Б/У', value: false}
  ],
  itemTypes: [
    {text: "Легкові та комерційні авто", itemValue: 1},
    {text: "СПЕЦІАЛЬНІ ТЗ", itemValue: 4},
    {text: "Вантажні авто", itemValue: 6},
    {text: "Обладнання", itemValue: 6},
    {text: "Причепи та Напівпричепи", itemValue: 6},
    {text: "Сільгосптехніка", itemValue: 6}
  ],
  clientTypes: [ 
    {text: "Юридична особа", value: 1}, 
    {text: "Фізична особа", value: 2}
  ],
  currencys: [ "UAH", "USD", "EUR" ],
  itemYears: [ "2019", "2020" ],
  oldItemYers: [ '2013', '2014', '2015', '2016', '2017', '2018', '2019' ],
  chartTypes: [
    // якщо вибраний індивідуальний графік на сервер прихована передаються дані в калькулято
    {text: "Класичний", value: 'even'},
    {text: "Ануїтет", value: 'annuity'},
    {text: "Індивідуальний", value: 'indv'}
  ],
  yesOrNo: [
    {text: "Так", value: 1},
    {text: "Нi", value: 2}
  ],
  gpsTrackers: [
    {text: 'Antenor з блокувань', value: 1}, 
    {text: 'ні', value: 2}, 
    {text: 'Benish Logistic з Блокування', value: 'waiting ..'},
    {text: 'Premium Benish Guard', value: 6},
    {text: 'Benish GuardPlatinum', value: 7},
    {text: 'Benish Universal з Блокування', value: 'waiting ..'}
  ],
  insurancePrograms: [
    {text: 'Стандарт', value: 1},
    {text: 'Обережно', value: 2},
    {text: 'Таксі', value: 'waiting ..'},
    {text: 'Тотал / крадіжка', value: 'waiting ..'}
  ],
  franchises: [
    {text: '0%', value: 1},
    {text: '0.3%', value: 2},
    {text: '0.5%', value: 3},
    {text: '1%', value: 4},
    {text: '2%', value: 5},
  ],
  regions: [
    'Вінницька область',
    'Волинська область',
    'Дніпропетровська область',
    'Донецька область',
    'Житомирська область',
    'Закарпатська область',
    'Запорізька область',
    'Івано-Франківська область',
    'Київська область',
    'Кіровоградська область',
    'Луганська область',
    'Львівська область',
    'Миколаївська область',
    'Одеська область',
    'Полтавська область',
    'Рівненська область',
    'Сумська область',
    'Тернопільська область',
    'Харківська область',
    'Харківська область',
    'Хмельницька область',
    'Черкаська область',
    'Чернівецька область',
    'Чернівецька область'
  ]
}