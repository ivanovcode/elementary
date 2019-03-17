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
    </style>
</head>

<body>

<div class="container">
    <form>
        <input class="u-full-width" type="text" placeholder="Email / Имя пользователя" id="Username">
        <input class="u-full-width" type="password" placeholder="Пароль" id="Password">
        <input class="button-primary" type="submit" value="Вход">
    </form>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    $( document ).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                xhrFields: {
                    withCredentials: true
                },
                headers: {
                    'Authorization': 'Basic ' + btoa($('#Username').val() + ':' + $('#Password').val())
                },
                url: "app/app.php?m=signin",
                success: function(data)
                {
                    location.reload();
                },
                error: function (e) {
                    alert('Ошибка авторизации');
                }
            });
        });
    });
</script>
</body>

</html>