<?php
require('function.php');

if(!empty($_POST)) {
    // 検索されたとき
    $word = "%".$_POST['word']."%";

    validMaxLen($word, "word", 50);
    if(empty($err_msg)) {
        try {
            $dbh = dbConnect();
            $sql = 'SELECT id, member_id, title, content, created_at 
                    FROM threads 
                    WHERE title LIKE :word OR content LIKE :word
                    ORDER BY created_at DESC
            ';
            $data = array(
                ':word' => $word
            );
            $stmt = queryPost($dbh, $sql, $data);
            $results = $stmt->fetchAll();
        } catch(Exception $e) {
            $err_msg['common'] = MSG09;
        }
    }
} else {
    // 検索していないとき
    try {
        $dbh = dbConnect();
        $sql = 'SELECT id, member_id, title, content, created_at 
                    FROM threads
                    ORDER BY created_at DESC
        ';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $results = $stmt->fetchAll();
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
        <form method="post" action="thread.php">
            <div class="form-group">
                <div class="form-inline">
                    <input type="text" style="width: 250px; padding: 5px 8px" name="word" value="<?php if(!empty($_POST)) echo $_POST['word'] ?>">
                </div>
                <div class="form-inline">
                    <input type="submit" class="bg-color_white" value="スレッド検索">
                </div>
                <div></div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['common'])) echo '＊'.$err_msg['common'];
                    ?>
                </div>
                <div class="thread-area">
                    <table>
                        <?php foreach ($results as $result): ?>
                            <tr>
                                <td><a href="thread_detail.php?id=<?php echo $result['id'] ?>&page=1">id:<?php echo $result['id'] ?></a></td>
                                <td style=><a href="thread_detail.php?id=<?php echo $result['id'] ?>&page=1"><?php echo $result['title'] ?></a></td>
                                <td><a href="thread_detail.php?id=<?php echo $result['id'] ?>&page=1"><?php echo date('Y.m.d H:i', strtotime($result['created_at'])) ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

            </div>
        </form>
        <div class="form-group btn-wrapper">
            <a class="btn btn-back" href="index.php">トップに戻る</a>
        </div>
    </div>

</main>
</body>
</html>
