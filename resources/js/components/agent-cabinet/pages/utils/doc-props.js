const legalDocs = [
  {text: 'Копія свідоцтва про державну реєстрацію та / або виписка з ЄДР', prop: 'state_registration_certificates'},
  {text: 'Статут', prop: 'regulations'},
  {text: 'Баланс та звіт про фінансові результати (Ф1 та Ф2)', prop: 'balances'},
  {text: 'Протокол засновників про обрання керівника (підписанта)', prop: 'protocols'},
  {text: 'Наказ про призначення керівника (підписанта)', prop: 'orders'},
  {text: 'Паспорт / ID-карта керівника (підписанта)', prop: 'passports'},
  {text: 'Довідка про присвоєння ІПН керівника (підписанта)', prop: 'taxNumbers'},
]
const personDocs = [
  {text: 'Паспорт громадянина України / ID-карта', prop: 'passports'},
  {text: 'Довідка про присвоєння ІПН', prop: 'taxNumbers'},
  {text: 'Довідка про заробітну плату', prop: 'salary_certificates'},
  {text: 'Паспорт дружини (чоловіка) позичальника', prop: 'relatives_passports'},
  {text: 'Довідка про фінансові документи', prop: 'referenceOfFinancialDocuments'},
]

export { legalDocs, personDocs }