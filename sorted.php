<?php

include_once "includes/functions.php";
$posts = get_posts(0, true); //возвращает все посты базы данных и сортируем от старых постов к новым 

$title = 'Сначала старые посты'; //название страницы

$error = get_error_message(); //ошибки при регистрации и входе

//подключение всех частей сайта
include_once "includes/header.php";
include_once "includes/posts.php";
include_once "includes/footer.php"; 

?>
