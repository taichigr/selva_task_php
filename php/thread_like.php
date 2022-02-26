<?php
require('function.php');

if(empty($_SESSION['login_date'])) {
    header('Location:member_regist.php');
    return;
}
if(!empty($_SESSION['login_date'])) {
    if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()) {
        session_destroy();
        header('Location:member_regist.php');
        return;
    }
}

if(!empty($_POST)) {
    $member_id = $_POST['member_id'];
    $comment_id = $_POST['comment_id'];
    $thread_id = $_POST['thread_id'];
    $pageNum = $_POST['pageNum'];

    try {
        $dbh = dbConnect();
        $sql1 = 'SELECT count(*) FROM likes WHERE member_id = :member_id AND comment_id = :comment_id';
        $data1 = array(
            ':member_id' => $member_id,
            ':comment_id' => $comment_id
        );
        $stmt1 = queryPost($dbh, $sql1, $data1);
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        if(empty(array_shift($result1))) {
            $sql2 = 'INSERT INTO likes (member_id, comment_id) 
                    VALUES (:member_id, :comment_id)
            ';
            $data2 = array(
                ':member_id' => $member_id,
                ':comment_id' => $comment_id
            );
            $stmt2 = queryPost($dbh, $sql2, $data2);
        } else {
            $sql3 = 'DELETE FROM likes WHERE member_id = :member_id AND comment_id = :comment_id';
            $data3 = array(
                ':member_id' => $member_id,
                ':comment_id' => $comment_id
            );
            $stmt3 = queryPost($dbh, $sql3, $data3);
        }
    } catch (Exception $e) {

    }
    header("Location:thread_detail.php?id=".$thread_id."&page=".$pageNum);
}

