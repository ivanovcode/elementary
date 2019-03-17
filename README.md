
**Скрипт ротации номера телефона на странице**

Поддержка использования как на .php страницах, так и .html c использованием javascript.

## Как использовать?

__1. C помощью файлового менеджера загрузить файлы app.php и config.ini в папку сайта__

__2. В файле config.ini указать список номеров__

__3. Вставить код на странице__

*PHP (Если фаил с расширением .php)*
```
//Подключаем библиотеку в начале страницы
<?php require('app.php'); ?>
```
```
//Выводим телефон там где необходимо, в одном или нескольких местах
<?=$phone?>
```
[ДЕМО PHP](http://debug.ivanov.site/rotate/index.php)



*JavaScript (без jQuery) (Если фаил с расширением .html)*
```
<script type="text/javascript">    
    //Подключаем библиотеку в конце страницы до закрывающего тега </body>
    let classname = "phone";
    let request=new XMLHttpRequest;request.open("GET","app.php?result=true",!0),request.onload=function(){if(request.status>=200&&request.status<400){let t=JSON.parse(request.responseText),s=document.getElementsByClassName(classname);for(var e=0;e<s.length;++e)s[e].innerHTML=t}},request.send();
</script>
```
```
<!--Выводим телефон там где необходимо, в одном или нескольких местах-->
<span class="phone"></span>
```

[ДЕМО JavaScript](http://debug.ivanov.site/rotate/index.html)

