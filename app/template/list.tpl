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
    </style>
</head>

<body>

<div class="container">
    <p>Вы авторизованны. <a href="#">Выйти</a></p>
    <form>
        <input class="u-full-width" type="text" placeholder="Новый номер" id="Phone">
        <input class="button-primary" type="submit" value="Добавить">
    </form>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Номер телефона</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                {list}
            </tbody>
        </table>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    $( document ).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "app/app.php?m=addphone",
                type: 'POST',
                data:{
                    phone: $("#Phone").val(),
                },
                success: function (response) {
                    location.reload();
                },
                error: function () {
                    alert('Ошибка добавления номера');
                }
            });
        });

        $('span').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "app/app.php?m=delphone",
                type: 'POST',
                data:{
                    phone: $(this).attr('data-value'),
                },
                success: function (response) {
                    location.reload();
                },
                error: function () {
                    alert('Ошибка удаления номера');
                }
            });
        });
        $('a').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "app/app.php?m=signout",
                type: 'GET',
                success: function (response) {
                    location.reload();
                },
                error: function () {
                    alert('Ошибка выхода из личного кабинета');
                }
            });
        });

    });
</script>
</body>

</html>
