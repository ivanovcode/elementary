<?php require('app/app.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/fontawesome.min.css">
    <style>
        .container {
            margin-top: 30px;
        }
        table {
            width: 100%;
        }
        span {
            cursor: pointer;
            border-bottom: dotted 1px #000;
        }
        td[data-key="Streams"] {
            color:#fff;
        }
        td[data-key="Streams"] span {
            display: inline-block;
            background-color:#33C3F0 ;
            border-bottom: none;
            padding:0px 5px;
            line-height: 20px;
            border-radius: 3px;
        }
        .hidden {
            display: none!important;
        }

    </style>
</head>

<body>
<div class="container">
    <h4>Пример работы скрипта</h4>

    <p>
[1] На странице ратируется номер телефона <b><?=$phone?></b>, номеров может быть несколько на странице <b><?=$phone?></b>.
    </p>

    <p>
[2] А так же номера могуть быть разными на странице <b><?=$phones[1]?></b> и <b><?=$phones[2]?></b>.
    </p>
    <p>
[3]  Вывод номера может меняться только поcле клика на cсылку,<br>
        Пример потока 2 <a href="#" <?=$_link[2]?>>[Нажми]</a> <b><?=$_phone[2]?></b><br>
        Пример потока 4 <a href="#" <?=$_link[4]?>>[Нажми]</a> <b><?=$_phone[4]?></b><br>
<i>... количество потоков возможно от 0 .. 99</i>
    </p>
    <p><i>Ссылка на описание и репозиторий:</i> <a href="https://github.com/profidela/elementary" target="_blank">https://github.com/profidela/elementary</a></p>
    <p><i>Перейти в Админку: </i> <a href="?m=admin" target="_blank">Личный кабинет</a></p>
    <p><i>Ревью изменений: </i> <a href="https://github.com/profidela/elementary/commit/67e6722ae2cb9981d60fbcb3f396d5d367256197" target="_blank">Изменения кода в версии 2.0)</a></p>

</div>
</body>
</html>


