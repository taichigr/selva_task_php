<?php
require('function.php');
$thread_id = (!empty($_GET['id'])) ? $_GET['id']: '';

if(!empty($thread_id)) {
    try {
        $stmt = getThreadDetail($thread_id);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $err_msg = MSG09;
    }
}



?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>スレッド詳細</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="header">
    <div class="header-left">
        <?php if(!empty($_SESSION['login_date'])): ?>
            <div class="header-msg">
                ようこそ
                <?php if(!empty($_SESSION['name_sei']) && $_SESSION['name_mei']) echo $_SESSION['name_sei'].$_SESSION['name_mei'] ?>
                様
            </div>
        <?php endif ?>
    </div>
    <div class="header-right">
        <ul>
            <li><a class="btn btn-header" href="thread.php">スレッド一覧に戻る</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="container">
        <h2><?php if(!empty($result)) echo $result['title'] ?></h2>
        <p class="thread-detail-time"><?php if(!empty($result)) echo date("m/d/y h:i", strtotime($result['created_at'])) ?></p>
        <div class="gray-area">
            <div class="gray-area-inner">
                <a href="">前へ</a>
                <a href="">次へ</a>
            </div>
        </div>        <div class="thread-content-area">
            <p>投稿者：<?php if(!empty($result)) echo $result['name_sei'].$result['name_mei'] ?>　<?php if(!empty($result)) echo date('Y.m.d H:i', strtotime($result['created_at'])) ?></p>
            <p><?php if(!empty($result)) echo $result['content'] ?></p>
        </div>
        <div class="gray-area">
            <div class="gray-area-inner">
                <a href="">前へ</a>
                <a href="">次へ</a>
            </div>
        </div>
        <?php
        if(!empty($_SESSION['login_date'])):
            if(($_SESSION['login_date'] + $_SESSION['login_limit']) > time()):
                ?>
                <form method="post" action="">
                    <div class="form-group">
                        <textarea style="width: 100%; height: 100px" name="comment" ></textarea>
                    </div>
                    <div class="form-group btn-right">
                        <input class="btn btn-default" type="submit" value="コメントする">
                    </div>
                </form>
            <?php
            endif;
        endif;
        ?>
    </div>
</main>
</body>
</html>
