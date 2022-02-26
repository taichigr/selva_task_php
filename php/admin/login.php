<?php
// ログイン画面
require('../function.php');
require('auth.php');

//======================
// ログイン処理
//======================
if(!empty($_POST)) {
    $login_id = $_POST['login_id'];
    $password = $_POST['password'];

    validRequired($login_id, 'login_id');
    validRequired($password, 'password');
    validHalfAndNumber($login_id, 'login_id');
    validHalfAndNumber($password, 'password');
    validLoginIdLength($login_id, 'login_id');
    validPasswordLength($password, 'password');
    if(empty($err_msg)) {
//         DB接続
        try {
            $dbh = dbConnect();
            $sql = 'SELECT password, id, name , login_id, deleted_at  FROM administers WHERE login_id = :login_id';
            $data = array(':login_id' => $login_id);
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!empty($result['deleted_at'])) {
                $err_msg['common'] = MSG15;
            } else {
                if(!empty($result) && $password == array_shift($result)) {
                    $_SESSION['admin_login_date'] = time();
                    $_SESSION['admin_login_limit'] = 60 * 60;
                    $_SESSION['admin_id'] = $result['id'];
                    $_SESSION['admin_name'] = $result['name'];
                    header("Location:index.php");
                } else {
                    $err_msg = array('');
                    $err_msg['login'] = MSG12;
                }
            }

        } catch (Exception $e) {
            $err_msg['common'] = MSG09;
        }
    } else {
        $err_msg = array('');
        $err_msg['login'] = MSG12;
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
    <link rel="stylesheet" href="../../css/style.css">
    <title>ログインページ</title>
</head>
<body>
<header class="admin-header">

</header>
<main>
    <div class="container">
        <h2>ログイン</h2>
        <form method="post" action="../admin/login.php">
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">ログインID</lavel>
                <input style="width: 260px;" type="text" name="login_id" required value="<?php if(!empty($login_id)) echo $login_id ?>">
            </div>
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">パスワード</lavel>
                <input style="width: 260px;" type="password" name="password" required>
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['login'])) echo '＊'.$err_msg['login'];
                ?>
                <?php
                if(!empty($err_msg['password'])) echo '＊'.$err_msg['password'];
                ?>
                <?php
                if(!empty($err_msg['common'])) echo '＊'.$err_msg['common'];
                ?>
            </div>
            <div class="form-group btn-wrapper">
                <input class="btn btn-default" type="submit" value="ログイン">
            </div>
        </form>
    </div>
    <footer class="admin-footer"></footer>
</main>
</body>
</html>