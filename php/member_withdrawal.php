<?php
require('function.php');
require('auth.php');
if(!empty($_POST)) {
    $id = $_POST['id'];
    try {
        $dbh = dbConnect();
        $sql = 'UPDATE members SET deleted_at = :deleted_at WHERE id = :id
            ';
        $data = array(
            ':deleted_at' => date('Y-m-d H:i:s'),
            ':id' => $id
        );
        $stmt = queryPost($dbh, $sql, $data);
        session_destroy();
        header("Location:index.php");
    } catch(Exception $e) {

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
    <title>スレッド詳細</title>
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.0.0.js" integrity="sha256-jrPLZ+8vDxt2FnE1zvZXCkCcebI/C8Dt5xyaQBjxQIo=" crossorigin="anonymous"></script>
<header class="header">
    <div class="header-left">

    </div>
    <div class="header-right">
        <ul>
            <li><a class="btn btn-header" href="index.php">トップに戻る</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="container">
        <h2>退会</h2>
        <div class="withdraw-container">
            <p>退会しますか？</p>
            <form action="member_withdrawal.php" method="post">
                <input type="hidden" name="id" value="<?php echo $_SESSION['member_id'] ?>">
                <button class="btn btn-default" type="submit">退会する</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
