
**Скрипт ротации номера телефона на странице**

Поддержка использования как на .php страницах, так и .html c использованием javascript.

## Как использовать?

__1. C помощью файлового менеджера загрузить папку app в папку сайта__

__2. Вставить код на странице__

*PHP (Если фаил с расширением .php)*
```

<?php 
    //Разместить в самом начале страницы
    require('app/app.php'); 
?>
```
```
//Выводим телефон там где необходимо, в одном или нескольких местах
<?=$phone?>


//Или выводим сразу разные номера c [N] номером телефона по порядку
<?=$phone[1]?>
<?=$phone[2]?>
<?=$phone[3]?>

```
[ДЕМО PHP](http://debug.ivanov.site/rotate/index.php)



*JavaScript (без jQuery) (Если фаил с расширением .html)*
```
<script type="text/javascript">
    //Разместить в конце страницы до закрывающего тега </body> 
    document.addEventListener("DOMContentLoaded", function(){ let request=new XMLHttpRequest;request.open("GET","app/app.php?m=echo",!0),request.onload=function(){if(request.status>=200&&request.status<400){let t=JSON.parse(request.responseText),s=document.getElementsByClassName("phone");for(var e=0;e<s.length;++e)s[e].innerHTML=t}},request.send();});
</script>
```
```
<!--Выводим телефон там где необходимо, в одном или нескольких местах-->
<span class="phone"></span>

```

[ДЕМО JavaScript](http://debug.ivanov.site/rotate/index.html)

__3. Редактировать фаил настроек app/app.ini и пароля входа в админку__

__4. Перейти в админ-панель авторизоваться  [http://ваша_веб_страница/?m=admin](http://ваш-сайт/ваша-страница/?m=admin)__

[ДЕМО Админка](http://debug.ivanov.site/rotate/?m=admin)

*Логин:* ``` demo2@demo.com ```
*Пароль:* ``` demo ```