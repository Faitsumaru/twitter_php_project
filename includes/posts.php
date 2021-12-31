<?php 
    if ($posts) { //если есть посты
?>

<section class="wrapper">
    <ul class="tweet-list">

    <!-- выводим наши посты из базы данных в цикле -->
        <?php foreach($posts as $post) { ?> 
            <?php //foreach($likes as $like) { ?>
            <li>
                <article class="tweet">
                    <div class="row">
                        <img class="avatar" src="<?php echo get_url($post['avatar']); ?>" alt="Аватар пользователя <?php echo $post['name']; ?>">
                        <div class="tweet__wrapper">
                            <header class="tweet__header">
                                <h3 class="tweet-author"><?php echo $post['name']; ?>
                                    <a href="<?php echo get_url('user_posts.php?id=' . $post['user_id']); ?>" class="tweet-author__add tweet-author__nickname"><?php echo $post['login']; ?></a>
                                    <time class="tweet-author__add tweet__date"><?php echo date('d.m.y в H:i', strtotime($post['date'])); ?></time>
                                </h3> <!-- strtotime- ф-я конвертации даты из строкового в числовое (unix time); ф-я date- преобразовывает дату в нужном вам формате-->
                                <?php if (logged_in() && $post['user_id'] == $_SESSION['user']['id']) { ?> <!-- Если id авторизовавшегося пользователя совпадает с id из сессии (те если только его посты, то выводим крестик) -->
                                    <a href="<?php echo get_url('includes/del_post.php?id=' . $post['id']); ?>" class="tweet__delete-button chest-icon"></a>
                                <?php } ?>    
                            </header>
                            <div class="tweet-post">
                                <p class="tweet-post__text"><?php echo $post['text']; ?></p>
                                <?php if($post['image'] != NULL) {?>
                                <figure class="tweet-post__image">
                                    <img src="<?php echo $post['image']; ?>" alt="tweet image">
                                </figure>
                                <?php } else echo ''?>
                            </div>
                        </div>
                    </div>
                    
                    <footer>
                        <?php 
                            $likes_count = get_likes_count($post['id']);
                            if (logged_in()) { //вывод красного/черного лайка и его удаление/добавление
                                    if (is_post_liked($post['id'])) { ?> 
                                        <a href="<?php echo get_url('includes/del_like.php?id=' . $post['id']); ?>" class="tweet__like tweet__like_active"><?php echo $likes_count; ?></a>
                                
                            <?php } else { ?> 
                                        <a href="<?php echo get_url('includes/add_like.php?id=' . $post['id']); ?>" class="tweet__like"><?php echo $likes_count; ?></a>
                            <?php }
                         } else { ?> 
                                <div class="tweet__like"><?php echo $likes_count; ?></div>
                            <?php } //div без возмодности постановки лайка для неавторизованных пользователей

                            // $class = ''; //делаем переменную пустой и по условию заполняем её
                            // if (logged_in() && is_post_liked($post['id'])) //показываем, только, когда пользователь авторизован и пост лайкнут (true)
                            //         $class = ' tweet__like_active';
                        ?>
                    
                    </footer>
                    
                </article>
            </li>
            <?php //break; } ?>
        <?php } ?>

    </ul>
</section>

<?php
    } 
    else {
        echo '<h3 class="tweet-form__title">Постов нет...</h3>';
    }
?>

<!--18-я строка - идентификатор пользователя при наведении на ссылку (ссылка каждого пользователя формируется с уникальным id)-->