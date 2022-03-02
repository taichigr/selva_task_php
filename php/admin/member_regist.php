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
    <link rel="stylesheet" href="../../css/style.css?<?php echo date("Ymd-Hi"); ?>">
    <title>会員登録</title>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.0.0.js" integrity="sha256-jrPLZ+8vDxt2FnE1zvZXCkCcebI/C8Dt5xyaQBjxQIo=" crossorigin="anonymous"></script>
<header class="admin-header">
    <div class="header-left">
        <h2>会員登録</h2>
    </div>
    <div class="header-right">
        <ul>
            <li><a class="btn btn-header" href="index.php">トップへ戻る</a></li>
        </ul>
    </div>
</header><main class="admin-main">
    <div class="container admin-container">
        <form method="post" action="member_regist.php">
            <div class="err-msg">
                <?php
                if(!empty($err_msg['common'])) echo $err_msg['common'];
                ?>
            </div>
            <div class="form-group">
                <lavel>ID</lavel>
                登録後に自動採番
            </div>
            <div class="form-group">
                氏名
                <div class="form-inline">
                    <lavel for="name_sei">姓</lavel>
                    <input type="text" name="name_sei" required value="<?php if(!empty($nameSei)) echo $nameSei; ?>">
                </div>
                <div class="form-inline">
                    <lavel for="name_mei">名</lavel>
                    <input type="text" name="name_mei" required value="<?php if(!empty($nameMei)) echo $nameMei; ?>">
                </div>
                <div></div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['name_sei'])) echo '＊氏名(姓)：'.$err_msg['name_sei'];
                    ?>
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['name_mei'])) echo '＊氏名(名)：'.$err_msg['name_mei'];
                    ?>
                </div>
            </div>
            <div class="form-group">
                性別
                <div class="form-inline">
                    <label><input type="radio" name="gender" value="1" required <?php if(!empty($gender) && $gender === "1") echo "checked" ?>>男性</label>
                </div>
                <div class="form-inline">
                    <label><input type="radio" name="gender" value="2" <?php if(!empty($gender) && $gender === "2") echo "checked" ?>>女性</label>
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['gender'])) echo '＊性別：'.$err_msg['gender'];
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="form-inline">住所</div>
                <lavel>都道府県</lavel>
                <select name="pref_name">
                    <option value="0">選択してください</option>
                    <?php foreach($prefectures as $prefecture): ?>
                        <option value="<?php echo $prefecture ?>" <?php if(!empty($prefName) && $prefecture === $prefName) echo 'selected'; ?>><?php echo $prefecture ?></option>
                    <?php endforeach; ?>
                </select>
                <div></div>
                <div style="margin-left: 37px; margin-top: 10px">
                    <lavel>それ以降の住所</lavel>
                    <input style="width: 225px;" type="text" name="address" value="<?php if(!empty($address)) echo $address ?>">
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['pref_name'])) echo '＊住所(都道府県)：'.$err_msg['pref_name'];
                    ?>
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['address'])) echo '＊住所(それ以降の住所)：'.$err_msg['address'];
                    ?>
                </div>

            </div>

            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">パスワード</lavel>
                <input style="width: 260px;" type="password" name="password" required>
            </div>
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">パスワード確認</lavel>
                <input style="width: 260px;" type="password" name="password-confirm" required>
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['password'])) echo '＊パスワード：'.$err_msg['password'];
                ?>
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['password-confirm'])) echo '＊パスワード確認：'.$err_msg['password-confirm'];
                ?>
            </div>
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">メールアドレス</lavel>
                <input style="width: 260px;" type="text" name="email" required value="<?php if(!empty($email)) echo $email ?>">
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['email'])) echo '＊メールアドレス：'.$err_msg['email'];
                ?>
            </div>

            <div class="form-group btn-wrapper">
                <input class="btn btn-default" type="submit" value="確認画面へ">
            </div>
            <div class="form-group btn-wrapper">
                <a class="btn btn-back" href="index.php" >トップに戻る</a>
            </div>
        </form>
    </div>



</main>
<?php //require('footer.php'); ?>
</body>
</html>