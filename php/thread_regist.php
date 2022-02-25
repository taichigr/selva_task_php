<?php
require('function.php');
require('auth.php');


if(!empty($_POST)){
    $title = $_POST['title'];
    $content = $_POST['content'];

    validRequired($title, 'title');
    validRequired($content, 'content');
    validMaxLen($title, 'title', 100);
    validMaxLen($content, 'content', 500);

    if(empty($err_msg)){
        $_SESSION['title'] = $title;
        $_SESSION['content'] = $content;
        header("Location:thread_regist_confirm.php");
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
    <title>スレッド作成フォーム</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php require('header.php'); ?>

<main>
    <div class="container">
        <h2>スレッド作成フォーム</h2>
        <form method="post" action="thread_regist.php">
            <div class="form-group">
                <lavel style="width: 115px;">スレッドタイトル</lavel>
                <input style="width: 260px;" type="text" name="title" required value="<?php if(!empty($title)) echo $title ?>">
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['title'])) echo '＊タイトル：'.$err_msg['title'];
                ?>
            </div>
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block; vertical-align: top">コメント</lavel>
                <textarea style="width: 273px;" type="text" name="content" required><?php if(!empty($content)) echo $content ?></textarea>
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['content'])) echo '＊コメント：'.$err_msg['content'];
                ?>
            </div>


            <div class="form-group btn-wrapper">
                <input class="btn btn-default" type="submit" value="確認画面へ">
            </div>
            <div class="form-group btn-wrapper">
                <a class="btn btn-back" href="index.php">トップに戻る</a>
            </div>
        </form>
    </div>

</main>
</body>
</html>
