<?php
print_r($_POST);
$post = $_POST;
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員情報確認</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main>
    <div class="container">
        <h2>会員情報登録フォーム</h2>
        <form method="post" action="member_regist_complete.php">
            <input type="hidden" name="name_sei" value="<?php echo $post['name_sei'] ?>">
            <input type="hidden" name="name_mei" value="<?php echo $post['name_mei'] ?>">
            <input type="hidden" name="gender" value="<?php echo $post['gender'] ?>">
            <input type="hidden" name="pref_name" value="<?php echo $post['pref_name'] ?>">
            <input type="hidden" name="address" value="<?php echo $post['address'] ?>">
            <input type="hidden" name="password" value="<?php echo $post['password'] ?>">
            <input type="hidden" name="email" value="<?php echo $post['email'] ?>">

            <div class="form-group">
                氏名
                <div class="confirm-area inline">
                    <?php echo $post['name_sei'].'　'.$post['name_mei'] ?>
                </div>

            </div>
            <div class="form-group">
                性別
                <div class="confirm-area inline">
                    <?php if($post['gender'] == 0){
                        echo '男性';
                    } else {
                        echo '女性';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="form-inline">住所</div>
                <div class="confirm-area inline">
                    <?php
                        echo $post['pref_name'].$post['address'];
                    ?>
                </div>
            </div>

            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">パスワード</lavel>
                セキュリティのため非表示
            </div>

            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">メールアドレス</lavel>
                <div class="confirm-area inline" style="color: #406bca">
                    <?php echo $post['email']; ?>
                </div>
            </div>

            <div class="form-group btn-wrapper">
                <input class="btn btn-default" type="submit" value="登録完了">
            </div>
            <div class="form-group btn-wrapper">
                <button class="btn btn-back" type="button" onclick="history.back()">前に戻る</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>