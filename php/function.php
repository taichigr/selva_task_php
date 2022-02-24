<?php
// セッションを利用
session_save_path("/var/tmp/");
ini_set('session.gc_maxlifetime', 60*60*24);
// クッキー自体の有効期限を伸ばす
ini_set('session.cookie_lifetime', 60*60*24);
session_start();
session_regenerate_id();
// エラー定数
define('MSG01', 'は入力必須です');
define('MSG02', '20字以内で入力してください');
define('MSG03', '100字以内で入力してください');
define('MSG04', '半角英数字で入力してください');
define('MSG05', '8~20字で入力してください');
define('MSG06', 'メールアドレスを入力してください');
define('MSG07', '200字以内で入力してください');
define('MSG08', 'パスワードと再入力が一致しません');
define('MSG09', 'エラーが発生しました');
define('MSG10', '都道府県を選択してください');
define('MSG11', 'メールアドレスが重複しています');
define('MSG12', 'IDもしくはパスワードが違います');
define('MSG13', '字以内で入力してください');




// グローバル変数
$err_msg = array();

// バリデーション関数
function validRequired($str, $key) {
    if($str === '') {
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}
function validNameLength($str, $key) {
    if(mb_strlen($str) > 20) {
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
function validPrefNameRequired($str, $key) {
    if($str == "0") {
        global $err_msg;
        $err_msg[$key] = MSG10;
    }
}
function validAddressLength($str, $key) {
    if(mb_strlen($str) > 100) {
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
// 半角英数字チェック
function validHalfAndNumber($str, $key) {
    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}
function validPasswordLength($str, $key) {
    if(mb_strlen($str) < 8 || mb_strlen($str) > 20){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
function validMatch($str1, $str2, $key) {
    if($str1 !== $str2) {
        global $err_msg;
        $err_msg[$key] = MSG08;
    }
}
function validEmailFormat($str, $key) {
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
function validEmailLength($str,$key) {
    if(mb_strlen($str) > 200) {
        global $err_msg;
        $err_msg[$key] = MSG07;
    }
}
// email重複チェック
function validEmailDuplication($email) {
    global $err_msg;
    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM members WHERE email = :email';
        $data = array(':email' => $email);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!empty(array_shift($result))){
            $err_msg['email'] = MSG11;
        }
    } catch (Exception $e) {
        echo $e;
        $err_msg['common'] = MSG09;
    }
}
function validMaxLen($str, $key, $max){
    if(mb_strlen($str) >= $max){
        global $err_msg;
        $err_msg[$key] = $max.MSG13;
    }
}
// 項目ごとのバリデーションまとめ
function validPassword($str, $key) {
    validRequired($key, $str);
    validHalfAndNumber($str, $key);
    validPasswordLength($str, $key);
}
function validEmail($str, $key) {
    validRequired($key, $str);
    validEmailFormat($str, $key);
    validEmailLength($str, $key);
}

//===============================
// データベース
//===============================
// DB接続関数
function dbConnect() {
    $dsn = 'mysql:dbname=selva_task;host=localhost;charset=utf8';
    $user = "root";
    $password = "root";
    $options = array(
        // SQL実行失敗時にはエラーコードのみ設定
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    // PDOオブジェクト生成
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}
function queryPost($dbh, $sql, $data) {
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}



//===============================
// 会員登録時の関数
//===============================

//===============================
// スレッド詳細の関数
//===============================
function getThreadDetail($thread_id) {
    $dbh = dbConnect();
    $sql = 'SELECT t.id, t.member_id, t.title, t.content, t.created_at, m.name_sei, m.name_mei
                    FROM threads AS t
                    LEFT JOIN members AS m
                    ON t.member_id = m.id
                    WHERE t.id = :id
            ';
    $data = array(
        ':id' => $thread_id
    );
    return $stmt = queryPost($dbh, $sql, $data);
}
