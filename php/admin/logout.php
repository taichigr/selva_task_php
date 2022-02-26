<?php
// adminログアウト処理
require('../function.php');
session_destroy();
// ログインページへ
header("Location:index.php");