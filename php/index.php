<?php
// トップ画面
require('function.php');
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css?<?php echo date("Ymd-Hi"); ?>">
    <title>トップページ</title>
</head>
<body>
<?php require('header.php'); ?>
<main>
    <div class="container">
        <h2>トップページ</h2>
    </div>
</main>
<?php require('footer.php'); ?>
</body>
</html>