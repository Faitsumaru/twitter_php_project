<?php

include_once "functions.php";

//проверка авторизации
if (!logged_in())
    redirect(); //редирект на главную, если пользователь не авторизован

if (isset($_GET['id']) && !empty($_GET['id'])) //проверка передачи текста сообщения (в textarea) (если текст и изображение есть)
    if (!del_post($_GET['id'])) //проверка на добавление поста (если не добавлен)
        $_SESSION['error'] = 'Во время удаления поста что-то пошло не так!';    

redirect(get_url('user_posts.php'));