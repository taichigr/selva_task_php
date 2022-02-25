<?php
//======================
// メンバー登録入力
//======================
require('function.php');
if(!empty($_POST)){
    $nameSei = $_POST['name_sei'];
    $nameMei = $_POST['name_mei'];
    $gender = $_POST['gender'];
    $prefName = $_POST['pref_name'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password-confirm'];
    $email = $_POST['email'];

    // バリデーション
    validRequired($nameSei, 'name_sei');
    validRequired($nameMei, 'name_mei');
    validRequired($gender, 'gender');
    validRequired($prefName, 'pref_name');
    validNameLength($nameSei, 'name_sei');
    validNameLength($nameMei, 'name_mei');
    validPrefNameRequired($prefName, 'pref_name');
    validAddressLength($address, 'address');
    validPassword($password, 'password');
    validMatch($password, $passwordConfirm, 'password');
    validEmail($email, 'email');
    validEmailDuplication($email);

    if(empty($err_msg)) {
        $_SESSION['name_sei'] = $nameSei;
        $_SESSION['name_mei'] = $nameMei;
        $_SESSION['gender'] = $gender;
        $_SESSION['pref_name'] = $prefName;
        $_SESSION['address'] = $address;
        $_SESSION['password'] = $password;
        $_SESSION['email'] = $email;

        header("Location:member_regist_confirm.php");
    }
}
$prefectures = array(
    1 => '北海道',
    2 => '青森県',
    3 => '岩手県',
    4 => '宮城県',
    5 => '秋田県',
    6 => '山形県',
    7 => '福島県',
    8 => '茨城県',
    9 => '栃木県',
    10 => '群馬県',
    11 => '埼玉県',
    12 => '千葉県',
    13 => '東京都',
    14 => '神奈川県',
    15 => '山梨県',
    16 => '長野県',
    17 => '新潟県',
    18 => '富山県',
    19 => '石川県',
    20 => '福井県',
    21 => '岐阜県',
    22 => '静岡県',
    23 => '愛知県',
    24 => '三重県',
    25 => '滋賀県',
    26 => '京都府',
    27 => '大阪府',
    28 => '兵庫県',
    29 => '奈良県',
    30 => '和歌山県',
    31 => '鳥取県',
    32 => '島根県',
    33 => '岡山県',
    34 => '広島県',
    35 => '山口県',
    36 => '徳島県',
    37 => '香川県',
    38 => '愛媛県',
    39 => '高知県',
    40 => '福岡県',
    41 => '佐賀県',
    42 => '長崎県',
    43 => '熊本県',
    44 => '大分県',
    45 => '宮崎県',
    46 => '鹿児島県',
    47 => '沖縄県'
);
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title>会員登録フォーム</title>
</head>
<body>
<main>
    <div class="container">
        <h2>会員情報登録フォーム</h2>
        <form method="post" action="member_regist.php">
            <div class="err-msg">
                <?php
                if(!empty($err_msg['common'])) echo $err_msg['common'];
                ?>
            </div>
            <div class="form-group">
                氏名
                <div class="form-inline">
                    <lavel for="name_sei">姓</lavel>
                    <input type="text" name="name_sei" required value="<?php if(!empty($nameSei)) echo $nameSei; ?>">
                </div>
                <div class="form-inline">
                    <lavel for="name_mei">名</lavel>
                    <input type="text" name="name_mei" required value="<?php if(!empty($nameMei)) echo $nameMei; ?>">
                </div>
                <div></div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['name_sei'])) echo '＊氏名(姓)：'.$err_msg['name_sei'];
                    ?>
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['name_mei'])) echo '＊氏名(名)：'.$err_msg['name_mei'];
                    ?>
                </div>
            </div>
            <div class="form-group">
                性別
                <div class="form-inline">
                    <label><input type="radio" name="gender" value="0" required <?php if(!empty($gender) && $gender === "0") echo "checked" ?>>男性</label>
                </div>
                <div class="form-inline">
                    <label><input type="radio" name="gender" value="1" <?php if(!empty($gender) && $gender === "1") echo "checked" ?>>女性</label>
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['gender'])) echo '＊性別：'.$err_msg['gender'];
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="form-inline">住所</div>
                <lavel>都道府県</lavel>
                <select name="pref_name">
                    <option value="0">選択してください</option>
                    <?php foreach($prefectures as $prefecture): ?>
                        <option value="<?php echo $prefecture ?>" <?php if(!empty($prefName) && $prefecture === $prefName) echo 'selected'; ?>><?php echo $prefecture ?></option>
                    <?php endforeach; ?>
                </select>
                <div></div>
                <div style="margin-left: 37px; margin-top: 10px">
                    <lavel>それ以降の住所</lavel>
                    <input style="width: 225px;" type="text" name="address" value="<?php if(!empty($address)) echo $address ?>">
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['pref_name'])) echo '＊住所(都道府県)：'.$err_msg['pref_name'];
                    ?>
                </div>
                <div class="err-msg">
                    <?php
                    if(!empty($err_msg['address'])) echo '＊住所(それ以降の住所)：'.$err_msg['address'];
                    ?>
                </div>

            </div>

            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">パスワード</lavel>
                <input style="width: 260px;" type="password" name="password" required>
            </div>
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">パスワード確認</lavel>
                <input style="width: 260px;" type="password" name="password-confirm" required>
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['password'])) echo '＊パスワード：'.$err_msg['password'];
                ?>
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['password-confirm'])) echo '＊パスワード確認：'.$err_msg['password-confirm'];
                ?>
            </div>
            <div class="form-group">
                <lavel style="width: 115px; display: inline-block">メールアドレス</lavel>
                <input style="width: 260px;" type="text" name="email" required value="<?php if(!empty($email)) echo $email ?>">
            </div>
            <div class="err-msg">
                <?php
                if(!empty($err_msg['email'])) echo '＊メールアドレス：'.$err_msg['email'];
                ?>
            </div>

            <div class="form-group btn-wrapper">
                <input class="btn btn-default" type="submit" value="確認画面へ">
            </div>
            <div class="form-group btn-wrapper">
                <a class="btn btn-back" href="index.php" >トップに戻る</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>