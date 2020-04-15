export default {
  itemConditions: [
    {text: 'Новий', value: 1},
    {text: 'Б/У', value: 2}
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
    {text: "Юридична особа", value: 2}, 
    {text: "Фізична особа", value: 1}
  ],
  currencys: [ "UAH", "USD", "EUR" ],
  itemYears: [ "2019", "2020" ],
  oldItemYers: [ '2013', '2014', '2015', '2016', '2017', '2018', '2019' ],
  chartTypes: [
    // якщо вибраний індивідуальний графік на сервер прихована передаються дані в калькулято
    "Класичний",
    "Ануїтет",
    "Індивідуальний"
  ],
  yesOrNo: [
    {text: "Так", value: 1},
    {text: "Нi", value: 2}
  ],
  gpsTrackers: [
    'Antenor з блокувань', 
    'ні', 
    'Benish Logistic з Блокування',
    'Premium Benish Guard',
    'Benish GuardPlatinum',
    'Benish Universal з Блокування'
  ],
  insurancePrograms: [
    'Стандарт',
    'Обережно',
    'Таксі',
    'Тотал / крадіжка'
  ],
  franchises: [
    {text: '0%', value: '0%'},
    {text: '0.3%', value: '0.3%'},
    {text: '0.5%', value: '0.5%'},
    {text: '1%', value: '1%'},
    {text: '2%', value: '2%'},
  ],
}