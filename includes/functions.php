<?php
include_once "config.php"; //подключение файла (единожды)

function get_url($page = '') { //исп для получения url контента (с полной ссылкой (вместе с http))
    return HOST . "/$page"; //конкатенация строк
}

function debug($var, $stop = false) { //ф-я проверки
    echo "<pre>";
    print_r($var);
    echo "</pre>";

    if ($stop) die;
}

function redirect($link = HOST) { //редирект на страницу (по умолчанию - на главную) (ф-я исп при авторизации и регистрации)
    header("Location: $link" );
    die;
}

///работа с базами данных///
function db() { //ф-я для подключения к базе данных
    try {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS, 
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //выброс ошибок
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //преобразование в ассоциативные массивы
        ]
        );
    } catch(PDOException $e) {
        die($e->getMessage()); //в случае ошибки выполения запроса
    }

}

function db_query($sql, $exec = false) { //ф-я запроса
    if (empty($sql)) //если пустой запрос
        return false; 

    if ($exec) //если запрос передали
        return db()->exec($sql);

    return db()->query($sql); //если запрос не передали
}
///----------------------///

function get_posts($user_id = 0, $sort = false) { //ф-я получения постов от пользователей (и сортировка по умолч - по убыванию (от новых к старым))
    $sorting = 'DESC';
    if($sort) //если отсортировано от старых к новым
        $sorting = 'ASC'; 

    if($user_id > 0) //проверка на корректность id (если был передан пользователь)
        return db_query(
            "SELECT posts.*, users.name, users.login, users.avatar 
            FROM `posts` 
            JOIN `users` 
            ON users.id = posts.user_id
            WHERE posts.user_id = $user_id
            ORDER BY posts.`date` $sorting; "
            )->fetchAll();
    return db_query( //(если не был передан пользователь)
        "SELECT posts.*, users.name, users.login, users.avatar 
        FROM `posts` 
        JOIN `users` 
        ON users.id = posts.user_id
        ORDER BY posts.`date` $sorting; "
        )->fetchAll(); //формироввание массива для перебора и прямого обращения
/*SELECT: выбираем все(*) посты, name, login avatar 
FROM: из таблицы post 
JOIN: присоединение к таблице users 
ON: по какому условию подставляются значения (по id) 
WHERE: вывод постов с определенным id 
ORDER BY: сортировка по дате (от новых постов к старым) (DESC - в обратном порядке, ASC - в прямом)
(тк id - общий в обеих таблицах) (таким образом, связали списки по ключу id)*/
}


function get_page_title($title='') { //ф-я для заголовка страницы
    if (!empty($title))
        return SITE_NAME . " - $title";
    else
        return SITE_NAME;
}

////////registration&authorization:

function get_user_info($login) { //информация о пользователе (вся строка в бд) (для проверки, существует ли уже пользователь) ('такой польз. уже есть')
    return db_query("SELECT * FROM `users` 
                    WHERE `login` = '$login';"
                    )->fetch(); //fetch() - преобр в массив только 1 строку
}

function add_user($login, $password) { //добавление пользователя в бд

    $login = trim($login); //убираем лишние пробелы вначале и в конце
    $name = ucfirst($login); //преобразуем первый символ логина в заглавный
    $password = password_hash($password, PASSWORD_DEFAULT); //шифрование пароля

    return db_query("INSERT INTO `users` (`id`, `login`, `pass`, `name`, `avatar`) 
                    VALUES (NULL, '$login', '$password', '$name');", true
                    );
}

function reg_user($auth_data) { //регистрация нового пользователя
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login']) || !isset($auth_data['passw1']) || empty($auth_data['passw2'])) 
        return false; //если пустой массив или не установлены логин и пароль или они пустые 
    
    $user = get_user_info($auth_data['login']);

    //ошибки при вводе в форму регистрации:
    if (!empty($user)) { // ошибка уже существующего пользователя
        $_SESSION['error'] = 'Пользователь ' . $auth_data['login'] . ' уже существует';
        redirect(get_url('register.php'));
    }
    if ($auth_data['passw1'] !== $auth_data['passw2']) { //ошибка некорректного ввода пароля
        $_SESSION['error'] = 'Пароли не совпадают';
        redirect(get_url('register.php'));
    }

    if (add_user($auth_data['login'], $auth_data['passw1'])) {
        redirect(get_url()); //(по умолч. на гл. стр.)
    }
    //debug($auth_data, true);
}

function log_user($auth_data) { //логин пользователя

    //если пользователь существует, то всё ОК, иначе в бд польз не сущ. и ошибка
    if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login'])) 
        return false;

    $user = get_user_info($auth_data['login']); //получаем информацию о пользователе

    //debug($user, true);
    if (empty($user)) { //проверка на сущ. польз-ля
        $_SESSION['error'] = 'Пользователь '. $auth_data['login'] .' не найден!';
        redirect(get_url()); //redirect на гл. страницу
    }
//ошибка несовпадения паролей
    if (password_verify($auth_data['pass'], $user['pass'])) { //верный пароль
        $_SESSION['user'] = $user;
        $_SESSION['error'] = ''; //ошибки нет
        redirect(get_url('user_posts.php?id=' . $user['id'])); //redirect на страницу с постами
    } else {
        $_SESSION['error'] = 'Пароль неверный'; //ошибка есть
        redirect(get_url());
    }
}
////////

function get_error_message() { //сообщение об ошибке
    $error = '';
    if (isset($_SESSION['error']) && !empty($_SESSION['error'])) { //если в сессии есть сообщение об ошибке и она не пустая
        $error = $_SESSION['error']; //то записываем сообщ об ошибке в переменную error
        $_SESSION['error'] = ''; //очистка сессии от ошибки
    }
    return $error;
}

function logged_in() { //ф-я проверки авторизации
    return isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']); //проверяет, есть ли id в сессии и не пустой ли он (и возвращает true/false)
}

///////работа с постами
function add_post($text, $image) { //ф-я добавления поста
    $text = trim($text); //убираем ненужные пробелы у текста
    if (mb_strlen($text) > 255) //ограничение по символам текста (mb_strlen - длина строки в мбайтах)
        $text = mb_substr($text, 0, 250) . ' ...'; // (mb_substr - ф-я возвр подстроку из строки)

    $user_id = $_SESSION['user']['id'];
    $sql = "INSERT INTO `posts` (`id`, `user_id`, `text`, `image`) 
            VALUES (NULL, '$user_id', '$text', '$image');";
    return db_query($sql, true);
}

function del_post($id) { //ф-я удаления поста
    if (is_numeric($id) > 0) { //проверка на число и > 0 

        $user_id = $_SESSION['user']['id'];
        $sql = "DELETE FROM `posts` 
                WHERE `id` = $id
                AND `user_id` = $user_id;"; //id - id поста, который нужно удалить; user_post - наш текущий пользователь
        return db_query($sql, true);
    }
}


///likes:
function get_likes_count($post_id) { //количество лайков
    if (empty($post_id)) 
        return 0;

    return db_query("SELECT COUNT(*) FROM `likes` WHERE `post_id` = $post_id;")->fetchColumn(); //запрос возвращает количество лайков для конкретного поста
}

function is_post_liked($post_id) { //проверка на лайк (лайкнут ли пост)
    $user_id = $_SESSION['user']['id'];
    if (empty($post_id)) //если id не передан 
        return false;

    return db_query("SELECT * FROM `likes` WHERE `post_id` = $post_id AND `user_id` = $user_id")->rowCount() > 0; //rowCount возвращает количество строк и возвращет true, если количество > 0 (есть лайки)
}

function add_like($post_id) { //ф-я добавления лайка
    $user_id = $_SESSION['user']['id'];
    if (empty($post_id))
        return false;

    $sql = "INSERT INTO `likes` (`user_id`, `post_id`)  
            VALUES ($user_id, $post_id);"; //запрос вставки лайков в таблицу
    return db_query($sql, true);
}

function del_like($post_id) { //ф-я удаления лайка
    $user_id = $_SESSION['user']['id'];
    if (empty($post_id))
        return false;

    return db_query("DELETE FROM `likes` WHERE `likes`.`user_id` = $user_id AND `likes`.`post_id` = $post_id;", true);
}


function get_liked_posts() { //ф-я отображения только лайкнутых постов
    $user_id = $_SESSION['user']['id'];

    $sql = "SELECT posts.*, users.name, users.login, users.avatar
            FROM `likes` 
            JOIN `posts` ON posts.id = likes.post_id 
            JOIN `users` ON users.id = posts.user_id WHERE likes.user_id = $user_id";
    return db_query($sql)->fetchAll(); //объединяем все 3 таблицы, сравниваем id постов с лайкнутыми постами, id пользователей с лайкнутыми постами, выбираем только лайкнутые посты
}