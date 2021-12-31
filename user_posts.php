<?php

include_once "includes/functions.php";

/* echo '<pre>';
var_dump($_GET['id']); //массив GET содержит информацию после знака вопроса (http://localhost/TwitterPrj/user_posts.php?id=1)
echo "</pre>";
die; */

$error = get_error_message();

if(isset($_SESSION['user']['id'])) //если пользователь авторизован, то мы берем инф из сессии
    $id = $_SESSION['user']['id']; //то запишем его в качестве id

else if (isset($_GET['id']) && !empty($_GET['id'])) //если id есть в массиве и не пустой (если передан через массив _GET, то берем из ссылки: '?id=...') 
    $id = $_GET['id']; //то в переменную id передаём идентификатор из массива

else 
    $id = 0; //id нет, если нет пользователя

$posts = get_posts($id); //возвращает все посты базы данных по id пользователей
// echo '<pre>'; //проверка
// print_r($posts);
// echo '</pre>';
// die;

$title = 'Твиты пользователя';

if (!empty($posts)) //если посты не пустые, то добавим доп. информацию
    $title = 'Твиты пользователя @' . $posts[0]['login'];

//подключение всех частей сайта
include_once "includes/header.php";
if (logged_in()) include "includes/tweet_form.php"; //тем, кто авторизован, выводим форму для твитов (для их поста)
include_once "includes/posts.php";
include_once "includes/footer.php"; 

?>
