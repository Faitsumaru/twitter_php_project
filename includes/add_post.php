<?php

include_once "functions.php";

//проверка авторизации
if (!logged_in())
    redirect(); //редирект на главную, если пользователь не авторизован

if (isset($_POST['text']) && !empty($_POST['text']) && isset($_POST['image'])) //проверка передачи текста сообщения (в textarea) (если текст и изображение есть)
    if (!add_post($_POST['text'], $_POST['image'])) //проверка на добавление поста (если не добавлен)
        $_SESSION['error'] = 'Во время добавления поста что-то пошло не так!';    

redirect(get_url('user_posts.php'));