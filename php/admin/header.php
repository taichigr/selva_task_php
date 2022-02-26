<!--　ヘッダー　-->
<script src="https://code.jquery.com/jquery-3.0.0.js" integrity="sha256-jrPLZ+8vDxt2FnE1zvZXCkCcebI/C8Dt5xyaQBjxQIo=" crossorigin="anonymous"></script>
<header class="admin-header">
    <div class="header-left">
        <h2>掲示板管理画面メインメニュー</h2>
    </div>
    <div class="header-right">
        <ul>
            <li>ようこそ　<?php echo $_SESSION['admin_name'] ?>　さん</li>
            <li><a class="btn btn-header" href="logout.php">ログアウト</a></li>
        </ul>
    </div>
</header>