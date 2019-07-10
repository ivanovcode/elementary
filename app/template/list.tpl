<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Личный кабинет</title>
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/fontawesome.min.css">

    <link rel="apple-touch-icon" sizes="76x76" href="app/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="app/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="app/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="app/assets/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

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
    <p>Вы авторизованны. <a href="#">Выйти</a></p>
    <form>
        <input class="u-full-width" type="text" placeholder="Номер телефона" id="Phone">
        <input class="u-full-width" type="text" placeholder="Потоки 1,2,3 ... 99" id="Streams">
        <input class="button-primary" type="submit" value="Создать новый">
        <input class="button hidden" type="button" value="Отменить">
    </form>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Номер телефона</th>
                <th scope="col">Потоки</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                {list}
            </tbody>
        </table>
    </div>
    <p><i>Ссылка на описание и репозиторий:</i> <a href="https://github.com/profidela/elementary" target="_blank">https://github.com/profidela/elementary</a></p>

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
                    streams: $("#Streams").val(),
                },
                success: function (response) {
                    location.reload();
                },
                error: function () {
                    alert('Ошибка добавления номера');
                }
            });
        });
        $("input[type='button']").click(function(e) {
            $("#Phone").val("");
            $("#Streams").val("");
            $("input[type='submit']").attr('value', 'Создать новый');
            $("input[type='button']").addClass('hidden');
        });

        $('#Phone').bind("change keyup input",function() {

            let value = $(this).val();
            let data_value = $(this).attr('data-value');

            if(value !== data_value) {
                $("input[type='submit']").attr('value', 'Создать новый');
                $("input[type='button']").addClass('hidden');
                $("#Streams").val("");
            }

            $(".table>tbody tr").each(function(i,elem) {
                let phone = $(this).find("td[data-key='Phone']").text();
                if(value === phone) {
                    console.log(phone);
                    let streams = $(this).closest("tr").find("td[data-key='Streams']").text();
                    $("#Phone").val(phone);
                    $("#Phone").attr('data-value', phone);
                    $("#Streams").val(streams);
                    $("input[type='submit']").attr('value', 'Обновить');
                    $("input[type='button']").removeClass('hidden');
                    return true;
                }
            });
        });

        $("span[data-mode='edit']").click(function(e) {
            e.preventDefault();
            let current_ = $(this);
            let phone = current_.closest("tr").find("td[data-key='Phone']").text();
            let streams = $(this).closest("tr").find("td[data-key='Streams']").text();

            $("#Phone").val(phone);
            $("#Phone").attr('data-value', phone);
            $("#Streams").val(streams);

            $("input[type='submit']").attr('value', 'Обновить');
            $("input[type='button']").removeClass('hidden');

        });
        $("span[data-mode='delete']").click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "app/app.php?m=delphone",
                type: 'POST',
                data:{
                    phone: $(this).closest("tr").find("td[data-key='Phone']").text(),
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
