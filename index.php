<?php

include_once "includes/functions.php";
$posts = get_posts(); //возвращает все посты базы данных

$title = 'Главная страница'; //название страницы

$error = get_error_message(); //ошибки при регистрации и входе

//подключение всех частей сайта
include_once "includes/header.php";
if (logged_in()) include "includes/tweet_form.php"; //тем, кто авторизован, выводим форму для твитов (для их поста)
include_once "includes/posts.php";
include_once "includes/footer.php"; 

?>
