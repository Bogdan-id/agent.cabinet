<DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>
            Новый запрос на регистрацию в личном кабинете агента
        </h2>
        <br>
        <h3>Email: </h3>
        <p>{{$user->email}}</p>
        <h3>Имя: </h3>
        <p>{{$user->name}}</p>
        <h3>Телефон: </h3><p>{{$user->phone}}</p>
        <h3>Принять: </h3>
        <button href="{{ url('verification/accept/5') }}" type="button" class="btn btn-default">Принять</button>
        <h3>Отклонить: </h3>
        <button href="{{ url('verification/reject/5') }}" type="button" class="btn btn-default">Отклонить</button>
    </body>
</html>