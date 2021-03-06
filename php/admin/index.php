<?php
// adminトップ画面
require('../function.php');
require('auth.php');
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../css/style.css">
    <title>トップページ</title>
</head>
<body>
<?php require('header.php'); ?>
<main class="admin-main">
    <div class="container admin-container">
        <h2>トップページ</h2>
        <div>
            <a class="btn btn-admin-member" href="member.php">会員一覧</a>
        </div>
    </div>
</main>
<?php //require('footer.php'); ?>
</body>
</html>