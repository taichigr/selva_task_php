<?php
//======================
// メンバー登録確認画面
//======================
require('../function.php');
$session = $_SESSION;
if(!empty($_POST)) {
    unset($_SESSION['id']);
    unset($_SESSION['editFlg']);
    unset($_SESSION['name_sei']);
    unset($_SESSION['name_mei']);
    unset($_SESSION['gender']);
    unset($_SESSION['pref_name']);
    unset($_SESSION['address']);
    unset($_SESSION['password']);
    unset($_SESSION['email']);


    // 課題　なんかよくわからんけど、パスワードだけアップデートできない

    if($session['editFlg'] === 'true') {
        // 編集　編集の場合、パスワードありとなしで場合わけ
        if(empty($session['password'])) {
            // パスワードなし
            echo 'パスワードなし編集';
            try {
                $dbh = dbConnect();
                $sql = 'UPDATE members 
                        SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender, pref_name = :pref_name, address = :address, email = :email, updated_at = :updated_at 
                        WHERE members.id = :id
            ';
                $data = array(
                    ':id' => $session['id'],
                    ':name_sei' => $_POST['name_sei'],
                    ':name_mei' => $_POST['name_mei'],
                    ':gender' => $_POST['gender'],
                    ':pref_name' => $_POST['pref_name'],
                    ':address' => $_POST['address'],
                    ':email' => $_POST['email'],
                    ':updated_at' => date('Y-m-d H:i:s'),
                );
                $stmt = queryPost($dbh, $sql, $data);
                header("Location:member.php");
            } catch (Exception $e) {
                $err_msg['common'] = MSG09;
            }
        } else {
            // パスワードあり
            try {
                echo "パスワードあり編集";
                $dbh = dbConnect();
                $sql = 'UPDATE members 
                        SET name_sei = :name_sei, name_mei = :name_mei, gender = :gender, pref_name = :pref_name, address = :address, password = :password, email = :email, updated_at = :updated_at 
                        WHERE id = :id
            ';
                $data = array(
                    ':id' => $session['id'],
                    ':name_sei' => $_POST['name_sei'],
                    ':name_mei' => $_POST['name_mei'],
                    ':gender' => $_POST['gender'],
                    ':pref_name' => $_POST['pref_name'],
                    ':address' => $_POST['address'],
                    ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    ':email' => $_POST['email'],
                    ':updated_at' => date('Y-m-d H:i:s'),
                );
                $stmt = queryPost($dbh, $sql, $data);
                header("Location:member.php");
            } catch (Exception $e) {
                $err_msg['common'] = MSG09;
            }
        }
    } else {
        // editFlgがfalseの時は新規登録
        echo '<br>';
        echo "editFlgがfalseの時の新規登録処理";
        try {
            echo '新規登録';
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
            header("Location:member.php");

        } catch(Exception $e) {
            $err_msg['common'] = MSG09;
        }
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
    <title><?php judgeEditOrRegist($session['editFlg'], '会員編集', '会員登録'); ?></title>
    <link rel="stylesheet" href="../../css/style.css?<?php echo date("Ymd-Hi"); ?>">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.0.0.js" integrity="sha256-jrPLZ+8vDxt2FnE1zvZXCkCcebI/C8Dt5xyaQBjxQIo=" crossorigin="anonymous"></script>
<header class="admin-header">
    <div class="header-left">
        <h2>
            <?php judgeEditOrRegist($session['editFlg'], '会員編集', '会員登録'); ?>
        </h2>
    </div>
    <div class="header-right">
        <ul>
            <li><a class="btn btn-header" href="member.php">一覧へ戻る</a></li>
        </ul>
    </div>
</header><main>
    <div class="container">
        <h2>会員情報登録フォーム</h2>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php if($session['id']) echo $session['id'] ?>">
            <input type="hidden" name="editFlg" value="<?php echo $session['editFlg'] ?>">
            <input type="hidden" name="name_sei" value="<?php echo $session['name_sei'] ?>">
            <input type="hidden" name="name_mei" value="<?php echo $session['name_mei'] ?>">
            <input type="hidden" name="gender" value="<?php echo $session['gender'] ?>">
            <input type="hidden" name="pref_name" value="<?php echo $session['pref_name'] ?>">
            <input type="hidden" name=" address" value="<?php echo $session['address'] ?>">
            <input type="hidden" name="password" value="<?php if($session['password']) echo $session['password'] ?>">
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
                    <?php if($session['gender'] == 1){
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
                <input class="btn btn-back" type="submit" value="<?php judgeEditOrRegist($session['editFlg'], '編集完了', '登録完了'); ?>">
            </div>
        </form>
    </div>
</main>
</body>
<script>
    $(function() {
        $('form').submit(function () {
            $(this).find(':submit').prop('disabled', 'true');
        });
    })
</script>
</html>