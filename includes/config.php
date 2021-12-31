<?php
//константы
define('SITE_NAME', 'Twitter');
define('HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/TwitterPrj'); //домен (SERVER - глобальный массив с названием домена (адрес сайта/имя pc))
define('DB_HOST', 'localhost'); //где находится база данных
define('DB_NAME', 'twitter'); //имя базы данных на phpMyAdmin
define('DB_USER', 'root'); //логин пользователя бд (default)
define('DB_PASS', '123'); //пароль пользователя бд (default)

session_start(); //сессия с инф о пользовтелях (массив)
