<!--　ヘッダー　-->
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
            <li><a class="btn btn-header" href="thread.php">スレッド一覧</a></li>
            <?php if(empty($_SESSION['login_date'])): ?>
                <li><a class="btn btn-header" href="member_regist.php">新規会員登録</a></li>
            <?php endif ?>
            <?php if(empty($_SESSION['login_date'])): ?>
                <li><a class="btn btn-header" href="login.php">ログイン</a></li>
            <?php endif ?>
            <?php if(!empty($_SESSION['login_date'])): ?>
                <li><a class="btn btn-header" href="thread_regist.php">新規スレッド作成</a></li>
            <?php endif ?>
            <?php if(!empty($_SESSION['login_date'])): ?>
                <li><a class="btn btn-header" href="logout.php">ログアウト</a></li>
            <?php endif ?>
        </ul>
    </div>
</header>