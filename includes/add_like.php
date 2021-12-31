<?php 

include_once "functions.php";

//проверка авторизации
if (!logged_in())
    redirect(); //редирект на главную, если пользователь не авторизован

if (isset($_GET['id']) && !empty($_GET['id'])) //проверка передачи id (если он есть)
    if (!add_like($_GET['id'])) //проверка на добавление id (если не добавлен)
        $_SESSION['error'] = 'Во время добавления лайка что-то пошло не так!';    

redirect();

?>