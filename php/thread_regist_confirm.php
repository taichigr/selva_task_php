<?php
require('function.php');
require('auth.php');
if(!empty($_POST)) {
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO
            threads (member_id, title, content, created_at, updated_at)
            VALUES (:member_id, :title, :content, :created_at, :updated_at)
            ';
        $data = array(
            ':member_id' => $_SESSION['member_id'],
            ':title' => $_POST['title'],
            ':content' => $_POST['content'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':updated_at' => date('Y-m-d H:i:s'),
        );
        $stmt = queryPost($dbh, $sql, $data);
        $_SESSION['title'] = "";
        $_SESSION['content'] = "";
        header("Location:index.php");
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
    <title>会員登録完了</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php require('header.php'); ?>

<main>
    <div class="container">
        <h2>スレッド作成確認画面</h2>
        <form method="post" action="thread_regist_confirm.php">
            <input type="hidden" name="title" value="<?php echo $_SESSION['title'] ?>">
            <input type="hidden" name="content" value="<?php echo $_SESSION['content'] ?>">

            <div class="form-group">
                タイトル
                <div class="confirm-area inline">
                    <?php echo $_SESSION['title'] ?>
                </div>
            </div>
            <div class="form-group">
                コメント
                <div class="confirm-area inline">
                    <?php echo $_SESSION['content'] ?>
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
<script>
    $(function() {
        $('form').submit(function () {
            $(this).find(':submit').prop('disabled', 'true');
        });
    })
</script>
</body>
</html>
