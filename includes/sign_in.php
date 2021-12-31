<?php

include_once "functions.php";

if (isset($_POST['login'])) //проверка с последующей отправкой или на повторный вход, или дальше на страницу
    log_user($_POST); 