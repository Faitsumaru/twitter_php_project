<?php

include_once "functions.php";

//проверка авторизации
if (!logged_in())
    redirect(); //редирект на главную, если пользователь не авторизован

if (isset($_GET['id']) && !empty($_GET['id'])) //проверка передачи id
    if (!del_like($_GET['id'])) //проверка на добавление лайка (если не добавлен)
        $_SESSION['error'] = 'Во время удаления лайка что-то пошло не так!';    

redirect();