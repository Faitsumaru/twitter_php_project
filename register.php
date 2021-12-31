<?php //подключение всех частей сайта для окна регистации
include_once "includes/functions.php";

if (logged_in()) //если пользователь уже авторизован, то мы со страницы с регистрацией отправляем его на гл. страницу
    redirect(get_url());

$title = 'Регистрация'; //название страницы
$error = get_error_message(); //сообщение об ошибке (если сообщ нет, то пустая строка)

include_once "includes/header.php";

include_once "includes/register_form.php";

include_once "includes/footer.php"; 

?>

