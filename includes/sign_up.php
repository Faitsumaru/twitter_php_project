<?php

include_once "functions.php";

if (isset($_POST['login'])) //проверка с последующей отправкой или на повторную регистрацию, или дальше на страницу
    reg_user($_POST); 

//если в массиве _POST есть переменная с ключем login, то вызываем ф-ю reg_user