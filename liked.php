<?php

include_once "includes/functions.php";

if (logged_in()) {
$posts = get_liked_posts(); //возвращает все посты базы данных и сортируем от старых постов к новым 

$title = 'Понравившиеся твиты'; //название страницы

$error = get_error_message(); //ошибки при регистрации и входе
}

//подключение всех частей сайта
include_once "includes/header.php";
if (logged_in()) 
    include "includes/posts.php"; //отображать лайкнутые посты только авторизованным пользователям
else redirect(); //иначе отправляем на главную страницу
include_once "includes/footer.php"; 

?>
