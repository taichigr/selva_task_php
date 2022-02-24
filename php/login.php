<?php
// ログイン画面
require('function.php');
require('auth.php');

//======================
// ログイン処理
//======================
if(!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    validEmail($email, 'email');
    validPassword($password, 'password');
    if(empty($err_msg)) {
        // DB接続
        try {
            $dbh = dbConnect();
            $sql = 'SELECT password, id, name_sei, name_mei FROM members WHERE email = :email';
            $data = array(':email' => $email);
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!empty($result) && password_verify($password, array_shift($result))) {
                $_SESSION['login_date'] = time();
                $_SESSION['login_limit'] = 60 * 60;
                $_SESSION['member_id'] = $result['id'];
                $_SESSION['name_sei'] = $result['name_sei'];
                $_SESSION['name_mei'] = $result['name_mei'];
                header("Location:index.php");
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
    <link rel="stylesheet" href="../css/style.css">
    <title>ログインページ</title>
</head>
<body>
<?php require('header.php'); ?>
<main>
    <div class="container">
        <h2>ログイン</h2>
        <form method="post" action="login.php">
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">メールアドレス（ID）</lavel>
                <input style="width: 260px;" type="text" name="email" required value="<?php if(!empty($email)) echo $email ?>">
            </div>
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">パスワード</lavel>
                <input style="width: 260px;" type="password" name="password" required>
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['login'])) echo '＊'.$err_msg['login'];
                ?>
            </div>





            <div class="form-group btn-wrapper">
                <input class="btn btn-default" type="submit" value="ログイン">
            </div>
            <div class="form-group btn-wrapper">
                <a class="btn btn-back" href="index.php" >トップに戻る</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>