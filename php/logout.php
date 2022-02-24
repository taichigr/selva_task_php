<?php
// ログアウト処理
require('function.php');
session_destroy();
// ログインページへ
header("Location:login.php");