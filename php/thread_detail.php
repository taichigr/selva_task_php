<?php
require('function.php');
$thread_id = (!empty($_GET['id'])) ? $_GET['id']: '';

if(!empty($thread_id)) {
    try {
        $stmt1 = getThreadDetail($thread_id);
        $result = $stmt1->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        $err_msg['common'] = MSG09;
        $err_msg['error_msg'] = $err_msg;
    }
    try {
        $dbh2 = dbConnect();
        $sql2 = 'SELECT count(*)
                    FROM comments 
                    WHERE thread_id = :id
            ';
        $data2 = array(
            ':id' => $thread_id
        );
        $stmt2 = queryPost($dbh2, $sql2, $data2);
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $commentCount = array_shift($result2);
        // ページネーション作成準備
        $count = $commentCount;
        $perPage = 5;
        $totalPageNum = ceil($count/$perPage);
        $currentPage = $_GET['page'];

    } catch (Exception $e) {
        $err_msg['common'] = MSG09;
        $err_msg['error_msg'] = $err_msg;
    }
    try {
        $dbh4 = dbConnect();
        $sql4 = 'SELECT c.id, c.member_id, c.thread_id, c.comment, c.created_at, m.name_sei, m.name_mei
                    FROM comments AS c
                    LEFT JOIN members AS m
                    ON c.member_id = m.id
                    WHERE thread_id = :id
                    ORDER BY c.created_at ASC 
                    LIMIT 5 OFFSET '.($_GET['page']-1)*5;

        $data4 = array(
            ':id' => $thread_id,
        );
        $stmt4 = queryPost($dbh4, $sql4, $data4);
        $comments = $stmt4->fetchAll();
        if(!empty($_SESSION['error_msg'])){
            $thread_error_msg = $_SESSION['error_msg'];
            $_SESSION['error_msg'] = array('');
        }
    } catch (Exception $e) {
        $err_msg['common'] = MSG09;
        $err_msg['error_msg'] = $err_msg;
    }
}

if(!empty($_POST)) {
    $member_id = $_SESSION['member_id'];
    $thread_id = $_POST['thread_id'];
    $comment = $_POST['comment'];
    validCommentRequired($comment, 'comment');
    validMaxLen($comment, 'comment', 500);
    if(empty($err_msg)) {
        try {
            $dbh3 = dbConnect();
            $sql3 = 'INSERT INTO comments (member_id, thread_id, comment, created_at, updated_at) 
                    VALUES (:member_id, :thread_id, :comment, :created_at, :updated_at)
            ';
            $data3 = array(
                    ':member_id' => $member_id,
                    'thread_id' => $thread_id,
                    'comment' => $comment,
                    ':created_at' => date('Y-m-d H:i:s'),
                    ':updated_at' => date('Y-m-d H:i:s'),
            );
            $stmt3 = queryPost($dbh3, $sql3, $data3);
            header("Location:thread_detail.php?id=".$thread_id."&page=1");
        } catch (Exception $e) {
            $err_msg['common'] = MSG09;
            $err_msg['error_msg'] = $err_msg;
        }
    } else {
        $_SESSION['error_msg'] = $err_msg;
        header("Location:thread_detail.php?id=".$thread_id."&page=1");
    }
}
//$commentIndexs = array();
//for($i = 1; $i <= 5; $i++){
//    $commentIndexs[] = (($currentPage-1)*5)+$i;
//}


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
    <link rel="stylesheet" href="../css/style.css?<?php echo date("Ymd-Hi"); ?>">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.0.0.js" integrity="sha256-jrPLZ+8vDxt2FnE1zvZXCkCcebI/C8Dt5xyaQBjxQIo=" crossorigin="anonymous"></script>
<header class="header">
    <div class="header-left">
        <?php if(!empty($_SESSION['login_date'])): ?>
            <div class="header-msg">
                ようこそ
                <?php if(!empty($_SESSION['name_sei']) && $_SESSION['name_mei']) echo $_SESSION['name_sei'].$_SESSION['name_mei'] ?>
                様
            </div>
        <?php endif ?>
    </div>
    <div class="header-right">
        <ul>
            <li><a class="btn btn-header" href="thread.php">スレッド一覧に戻る</a></li>
        </ul>
    </div>
</header>

<main>
    <div class="container">
        <h2><?php if(!empty($result)) echo $result['title'] ?></h2>
        <p class="thread-detail-time"><?php echo $commentCount ?>コメント　　<?php if(!empty($result)) echo date("m/d/y h:i", strtotime($result['created_at'])) ?></p>
        <div class="gray-area" style="margin-top: 10px;">
            <div class="gray-area-inner">
                <?php pagination($totalPageNum, $thread_id, $currentPage); ?>
            </div>
        </div>
        <div class="thread-content-area">
            <p class="thread-detail-namearea">投稿者：<?php if(!empty($result)) echo $result['name_sei'].$result['name_mei'] ?>　<?php if(!empty($result)) echo date('Y.m.d H:i', strtotime($result['created_at'])) ?></p>
            <p class="thread-detail-content"><?php if(!empty($result)) echo nl2br($result['content']) ?></p>
        </div>
        <?php if(!empty($comments)): ?>
            <?php foreach($comments as $index => $comment): ?>
                <div class="comment-area">
                    <p class="comment-area-username"><?php echo ($index+1)+($currentPage-1)*5 ?>. <?php echo $comment['name_sei'].'　'.$comment['name_mei'] ?>　<?php echo date("Y.m.d h:i", strtotime($comment['created_at'])) ?></p>
                    <p class="comment-area-comment"><?php echo nl2br($comment['comment']) ?></p>
                    <div class="icon-area">
                        <form action="thread_like.php" method="post">
                            <input type="hidden" name="member_id" value="<?php echo $_SESSION['member_id']; ?>">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                            <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
                            <input type="hidden" name="pageNum" value="<?php echo $currentPage; ?>">
                            <button class="btn-submit" style="background: none; border: none;" type="submit"><i class="fa-regular fa-heart <?php watchUserLike($_SESSION['member_id'], $comment['id']); ?>"></i></button><?php echo getLikeCount($comment['id']); ?>
                        </form>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="gray-area">
            <div class="gray-area-inner">
                <?php pagination($totalPageNum, $thread_id, $currentPage); ?>
            </div>
        </div>
        <?php
        if(!empty($_SESSION['login_date'])):
            if(($_SESSION['login_date'] + $_SESSION['login_limit']) > time()):
                ?>
                <form method="post" action="thread_detail.php">
                    <div class="form-group">
                        <textarea style="width: 100%; height: 100px" name="comment"></textarea>
                        <input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
                    </div>
                    <div class="err-msg">
                        <?php
                        if(!empty($thread_error_msg['comment'])) {
                            echo '＊' . $thread_error_msg['comment'];
                        }
                        ?>
                        <?php
                        if(!empty($thread_error_msg['comment']['common'])) {
                            echo '＊' . $thread_error_msg['common'];
                        }
                        ?>
                    </div>
                    <div class="form-group btn-right">
                        <input class="btn btn-default" type="submit" value="コメントする">
                    </div>
                </form>
            <?php
            endif;
        endif;
        ?>
    </div>
</main>
</body>
</html>
