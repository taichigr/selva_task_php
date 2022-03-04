<?php
//======================
// メンバー登録確認画面
//======================
require('../function.php');


if(!empty($_GET)) {
    // 編集処理の最初の画面表示
    $getMethodResult = getUser($_GET['id']);
    // 代入
    $id = $_GET['id'];
    $nameSei = $getMethodResult['name_sei'];
    $nameMei = $getMethodResult['name_mei'];
    $gender = $getMethodResult['gender'];
    $prefName = $getMethodResult['pref_name'];
    $address = $getMethodResult['address'];
    $password = $getMethodResult['password'];
    $email = $getMethodResult['email'];
}

if(!empty($_POST)) {
    $id = $_POST['id'];
    try {
        $dbh = dbConnect();
        $sql = 'UPDATE members 
                        SET deleted_at = :deleted_at 
                        WHERE id = :id
            ';
        $data = array(
            ':id' => $id,
            ':deleted_at' => date('Y-m-d H:i:s'),
        );
        $stmt = queryPost($dbh, $sql, $data);
        header("Location:member.php");
    } catch (Exception $e) {
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
    <title>会員詳細</title>
    <link rel="stylesheet" href="../../css/style.css?<?php echo date("Ymd-Hi"); ?>">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.0.0.js" integrity="sha256-jrPLZ+8vDxt2FnE1zvZXCkCcebI/C8Dt5xyaQBjxQIo=" crossorigin="anonymous"></script>
<header class="admin-header">
    <div class="header-left">
        <h2>
            会員詳細
        </h2>
    </div>
    <div class="header-right">
        <ul>
            <li><a class="btn btn-header" href="member.php">一覧へ戻る</a></li>
        </ul>
    </div>
</header>
<main>
    <div class="container">
        <h2>会員情報登録フォーム</h2>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-group">
                ID
                <div class="confirm-area inline">
                    <?php echo $id ?>
                </div>
            </div>
            <div class="form-group">
                氏名
                <div class="confirm-area inline">
                    <?php echo $nameSei.'　'.$nameMei ?>
                </div>

            </div>
            <div class="form-group">
                性別
                <div class="confirm-area inline">
                    <?php if($gender == 1){
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
                    echo $prefName.$address;
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
                    <?php echo $email; ?>
                </div>
            </div>

            <div class="form-group btn-wrapper">
                <a class="btn btn-back" href="member_regist.php?id=<?php echo $id ?>">編集</a>
            </div>
            <div class="form-group btn-wrapper">
                <input class="btn btn-back" type="submit" value="削除">
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