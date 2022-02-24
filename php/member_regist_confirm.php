<?php
//======================
// メンバー登録確認画面
//======================
require('function.php');
$session = $_SESSION;
if(!empty($_POST)) {
    session_unset();
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO
            members (name_sei, name_mei, gender, pref_name, address, password, email, created_at, updated_at)
            VALUES (:name_sei, :name_mei, :gender, :pref_name, :address, :password, :email, :created_at, :updated_at)
            ';
        $data = array(
            ':name_sei' => $_POST['name_sei'],
            ':name_mei' => $_POST['name_mei'],
            ':gender' => $_POST['gender'],
            ':pref_name' => $_POST['pref_name'],
            ':address' => $_POST['address'],
            ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            ':email' => $_POST['email'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s'),
        );
        $stmt = queryPost($dbh, $sql, $data);
        $sesLimit = 60*60;
        $_SESSION['login_date'] = time();
        $_SESSION['login_limit'] = $sesLimit;
        // ユーザー情報
        $_SESSION['user_id'] = $dbh->lastInsertId();
        $_SESSION['name_sei'] = $session['name_sei'];
        $_SESSION['name_mei'] = $session['name_mei'];

        header("Location:member_regist_complete.php");

    } catch(Exception $e) {
        $err_msg['common'] = MSG09;
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
    <title>会員情報確認</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<main>
    <div class="container">
        <h2>会員情報登録フォーム</h2>
        <form method="post" action="member_regist_confirm.php">
            <input type="hidden" name="name_sei" value="<?php echo $session['name_sei'] ?>">
            <input type="hidden" name="name_mei" value="<?php echo $session['name_mei'] ?>">
            <input type="hidden" name="gender" value="<?php echo $session['gender'] ?>">
            <input type="hidden" name="pref_name" value="<?php echo $session['pref_name'] ?>">
            <input type="hidden" name=" address" value="<?php echo $session['address'] ?>">
            <input type="hidden" name="password" value="<?php echo $session['password'] ?>">
            <input type="hidden" name="email" value="<?php echo $session['email'] ?>">

            <div class="form-group">
                氏名
                <div class="confirm-area inline">
                    <?php echo $session['name_sei'].'　'.$session['name_mei'] ?>
                </div>

            </div>
            <div class="form-group">
                性別
                <div class="confirm-area inline">
                    <?php if($session['gender'] == 0){
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
                    echo $session['pref_name'].$session['address'];
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
                    <?php echo $session['email']; ?>
                </div>
            </div>

            <div class="form-group btn-wrapper">
                <input class="btn btn-default" type="submit" value="登録完了">
            </div>
            <div class="form-group btn-wrapper">
                <button class="btn btn-back" type="button" onclick="history.back()">前に戻る</button>
            </div>
            <div class="form-group btn-wrapper">
                <a class="btn btn-back" href="index.php" >トップに戻る</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>