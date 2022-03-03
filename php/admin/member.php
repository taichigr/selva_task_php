<?php
// adminトップ画面
require('../function.php');
require('auth.php');

// デフォルトは降順

// 現在ページ
$currentPageNum = (!empty($_GET['page'])) ? $_GET['page']: 1;
// 表示件数
$listSpan = 10;
$currentMinNum = (($currentPageNum - 1)* $listSpan);

try {
    // 初期表示全部表示
    $dbh1 = dbConnect();
    $sql1 = 'SELECT count(*) FROM members';
    $data1 = array();
    $stmt1 = queryPost($dbh1, $sql1, $data1);
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

    $dbh2 = dbConnect();
    $sql2 = 'SELECT id, name_sei, name_mei, gender, pref_name, address, created_at
                    FROM members
                    ORDER BY id DESC
            ';
    $sql2 .= ' LIMIT '.$listSpan.' OFFSET '. $currentMinNum;

    $data2 = array(
    );
    $stmt2 = queryPost($dbh2, $sql2, $data2);
    $results = $stmt2->fetchAll();

    // ページネーション準備
    $countMembers = array_shift($result1);
    $totalPageNum = ceil($countMembers/$listSpan);
} catch (Exception $e) {
    $err_msg['common'] = MSG09;
}

if(!empty($_GET)) {
    !empty($_GET['id'])? $id = $_GET['id']: $male = '';
    !empty($_GET['male'])? $male = $_GET['male']: $male = '';
    !empty($_GET['female'])? $female = $_GET['female']: $female = '';
    !empty($_GET['pref_name'])? $pref_name = $_GET['pref_name']: $pref_name = '';
    if($pref_name === 0) $pref_name = '';
    !empty($_GET['free'])? $free = "%".$_GET['free']."%": $free = '';
    if(!empty($_GET['orderById'])) $orderById = $_GET['orderById'];
    if(!empty($_GET['orderByCreatedAt'])) $orderByCreatedAt = $_GET['orderByCreatedAt'];


    if(empty($id) && empty($pref_name) && empty($free) && empty($male) && empty($female)) {
        // 全部空のとき
        $dbh = dbConnect();
        $sqlCount = 'SELECT count(*) FROM members';

        $sql = 'SELECT id, name_sei, name_mei, gender, pref_name, address, created_at 
                    FROM members 
            ';
        $sqlAttach = '';
        if(!empty($orderById)){
            if($orderById === "desc") {
                $sqlAttach .= ' ORDER BY id DESC';
                $orderById = "asc";
            } else {
                $sqlAttach .= ' ORDER BY id ASC';
                $orderById = "desc";
            }
        }
        if(!empty($orderByCreatedAt)) {
            if($orderByCreatedAt === "desc") {
                $sqlAttach .= ' ORDER BY created_at DESC';
                $orderByCreatedAt = "asc";
            } else {
                $sqlAttach .= ' ORDER BY created_at ASC';
                $orderByCreatedAt = "desc";
            }
        }
        if(empty($orderById) && empty($orderByCreatedAt)) {
            $sqlAttach .= ' ORDER BY id DESC';
        }
        $sqlCount .= $sqlAttach;
        $sql .= $sqlAttach;
        $sql .= ' LIMIT '.$listSpan.' OFFSET '. $currentMinNum;
        $data = array(
        );
        $stmtCount = queryPost($dbh, $sqlCount, $data);
        $resultCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
        $stmt = queryPost($dbh, $sql, $data);
        $results = $stmt->fetchAll();
        // ページネーション準備
        $countMembers = array_shift($resultCount);
        $totalPageNum = ceil($countMembers/$listSpan);
    } else {
        // ORは（）で囲う
            try {
                $dbh = dbConnect();
                $sqlCount = 'SELECT count(*) FROM members WHERE';
                $sql = 'SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE';
                // $idがあるときの処理
                $sqlAttach = '';
                if(!empty($id)) {
                    $sqlAttach .= ' id = :id';
                    if(!empty($male) && !empty($female)) {
                        $sqlAttach .= ' AND (gender = :male OR gender = :female)';
                    } elseif(!empty($male) && empty($female)) {
                        $sqlAttach .= ' AND gender = :male';
                    } elseif(!empty($female) && empty($male)) {
                        $sqlAttach .= ' AND gender = :female';
                    }
                    if($pref_name !== "") {
                        $sqlAttach .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sqlAttach .= ' AND (name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free)';
                    }
                }
                // $idがなくて、性別があるときの処理
                if(empty($id) && !empty($male) && !empty($female)) {
                    $sqlAttach .= ' (gender = :male OR gender = :female)';
                    if($pref_name !== "") {
                        $sqlAttach .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sqlAttach .= ' AND (name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free)';
                    }
                } elseif(empty($id) && !empty($male) && empty($female)) {
                    $sqlAttach .= ' gender = :male';
                    if($pref_name !== "") {
                        $sqlAttach .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sqlAttach .= ' AND (name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free)';
                    }
                } elseif(empty($id) && !empty($female) && empty($male)) {
                    $sqlAttach .= ' gender = :female';
                    if($pref_name !== "") {
                        $sqlAttach .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sqlAttach .= ' AND (name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free)';
                    }
                }
                // $id,性別がないときの処理
                if(empty($id) && empty($male) && empty($female) && $pref_name !== "") {
                    $sqlAttach .= ' pref_name = :pref_name';
                    if(!empty($free)) {
                        $sqlAttach .= ' AND (name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free)';
                    }
                }
                // $id, 性別、$pref_nameがないときの処理
                if(empty($id) && empty($male) && empty($female) && $pref_name === "" && !empty($free)) {
                    $sqlAttach .= ' (name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free)';
                }
                // 昇順降順
                if(!empty($orderById)){
                    if($orderById === "desc") {
                        $sqlAttach .= ' ORDER BY id DESC';
                        $orderById = "asc";
                    } else {
                        $sqlAttach .= ' ORDER BY id ASC';
                        $orderById = "desc";
                    }
                }
                if(!empty($orderByCreatedAt)) {
                    if($orderByCreatedAt === "desc") {
                        $sqlAttach .= ' ORDER BY created_at DESC';
                        $orderByCreatedAt = "asc";
                    } else {
                        $sqlAttach .= ' ORDER BY created_at ASC';
                        $orderByCreatedAt = "desc";
                    }
                }
                if(empty($orderById) && empty($orderByCreatedAt)) {
                    $sqlAttach .= ' ORDER BY id DESC';
                }
                $sqlCount .= $sqlAttach;
                $sql .= $sqlAttach;
                $sql .= ' LIMIT '.$listSpan.' OFFSET '. $currentMinNum;


                $data = array();
                if(!empty($id)) {
                    $data[':id'] = $id;
                }
                if(!empty($male)) {
                    $data['male'] = $male;
                }
                if(!empty($female)) {
                    $data['female'] = $female;
                }
                if(!empty($pref_name)) {
                    $data['pref_name'] = $pref_name;
                }
                if(!empty($free)) {
                    $data['free'] = $free;
                }

                $stmtCount = queryPost($dbh, $sqlCount, $data);
                $resultCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
                $stmt = queryPost($dbh, $sql, $data);
                $results = $stmt->fetchAll();
                // ページネーション準備
                $countMembers = array_shift($resultCount);
                $totalPageNum = ceil($countMembers/$listSpan);
            } catch (Exception $e) {
                $err_msg['common'] = MSG09;
            }
        }
}


function returnRequestUrlPage($url) {
    if(strpos($url, 'page=') !== false) {
        $newurl = mb_substr($url, 0, -1);
        return $newurl;
    }
    $lastStr = substr($url, -1);
    $lastStrDescAsc = substr($url, -2);
    $lastStrPhp = substr($url, -3);
    if($lastStr === '?'){
        return $url.'page=';
    } elseif($lastStr === '=') {
        return $url. '&page=';
    } else {
        if($lastStrDescAsc === 'sc') {
            return $url. '&page=';
        } else {
            if($lastStrPhp === 'php') {
                return $url. '?page=';
            }else {
                return $url.'&page=';
            }
        }
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
    <link rel="stylesheet" href="../../css/style.css?<?php echo date("Ymd-Hi"); ?>">
    <title>会員一覧</title>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.0.0.js" integrity="sha256-jrPLZ+8vDxt2FnE1zvZXCkCcebI/C8Dt5xyaQBjxQIo=" crossorigin="anonymous"></script>
<header class="admin-header">
    <div class="header-left">
        <h2>会員一覧</h2>
    </div>
    <div class="header-right">
        <ul>
            <li><a class="btn btn-header" href="index.php">トップへ戻る</a></li>
        </ul>
    </div>
</header><main class="admin-main">
    <div class="container admin-container">
        <div style="height: 40px">
            <a class="btn btn-default" href="member_regist.php">会員登録</a>
        </div>
        <div class="member-form-container">
            <form id="member-search" action="member.php" method="get">
                <table class="member-table">
                    <tr>
                        <td class="member-table-left">ID</td>
                        <td class="member-table-right"><input type="text" name="id" value="<?php if(!empty($_GET['id'])) echo $_GET['id'] ?>"></td>
                    </tr>
                    <tr>
                        <td class="member-table-left">性別</td>
                        <td class="member-table-right">
                            <div class="form-inline">
                                <label><input type="checkbox" name="male" value="1" <?php if(!empty($_GET['male']) && $_GET['male'] === "1") echo "checked" ?>>男性</label>
                            </div>
                            <div class="form-inline">
                                <label><input type="checkbox" name="female" value="2" <?php if(!empty($_GET['female']) && $_GET['female'] === "2") echo "checked" ?>>女性</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="member-table-left">都道府県</td>
                        <td class="member-table-right">
                            <select name="pref_name">
                                <option value="0">選択してください</option>
                                <?php foreach($prefectures as $prefecture): ?>
                                    <option value="<?php echo $prefecture ?>" <?php if(!empty($_GET['pref_name']) && $prefecture === $_GET['pref_name']) echo 'selected'; ?>><?php echo $prefecture ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="member-table-left">フリーワード</td>
                        <td class="member-table-right"><input type="text" name="free" value="<?php if(!empty($_GET['free'])) echo $_GET['free'] ?>"></td>
                    </tr>
                </table>
                <div class="btn-wrapper">
                    <input class="btn btn-admin-member" type="submit" value="検索する">
                </div>
            </form>
        </div>

        <div class="member-show-container">
            <table class="member-showtable">
                <tr class="member-showtable-header">
                    <th>
                        ID
                        <button name="orderById" value="<?php echo !empty($orderById)? $orderById: $orderById = "asc" ?>" class="submit-order" type="submit" form="member-search">▼</button>
                    </th>
                    <th>氏名</th>
                    <th>性別</th>
                    <th>住所</th>
                    <th>
                        登録日時
                        <button name="orderByCreatedAt" value="<?php echo !empty($orderByCreatedAt)? $orderByCreatedAt: $orderByCreatedAt = "asc" ?>" class="submit-order" type="submit" form="member-search">▼</button>
                    </th>
                    <th>編集</th>
                </tr>
                <?php if(!empty($results)): ?>
                    <?php foreach ($results as $result): ?>
                        <tr class="member-showtable-body">
                            <td><?php echo $result['id'] ?></td>
                            <td><?php echo $result['name_sei'] ?>　<?php echo $result['name_mei'] ?></td>
                            <td><?php echo showGender($result['gender']) ?></td>
                            <td><?php echo $result['pref_name'].$result['address'] ?></td>
                            <td><?php echo date('Y/m/d', strtotime($result['created_at'])) ?></td>
                            <td style="width: 45px"><a href="member_regist.php?id=<?php echo $result['id'] ?>">編集</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>

        <div class="pagination">
            <?php if(!empty($totalPageNum)): ?>
                <ul class="pagination-list">
                    <?php
                    $pageColNum = 3;
                    // 現在のページが、総ページ数と同じ　かつ　総ページ数が表示項目数以上なら、左にリンク2個出す
                    if( $currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
                        $minPageNum = $currentPageNum - 2;
                        $maxPageNum = $currentPageNum;
                        // 現在のページが、総ページ数の１ページ前なら、左にリンク1個、右に１個出す
                    }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
                        $minPageNum = $currentPageNum - 1;
                        $maxPageNum = $currentPageNum + 1;
                        // 現ページが2の場合は左にリンク１個、右にリンク1個だす。
                    }elseif( $currentPageNum == 2 && $totalPageNum >= $pageColNum){
                        $minPageNum = $currentPageNum - 1;
                        $maxPageNum = $currentPageNum + 1;
                        // 現ページが1の場合は左に何も出さない。右に2個出す。
                    }elseif( $currentPageNum == 1 && $totalPageNum >= $pageColNum){
                        $minPageNum = $currentPageNum;
                        $maxPageNum = 3;
                        // 総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
                    }elseif($totalPageNum < $pageColNum){
                        $minPageNum = 1;
                        $maxPageNum = $totalPageNum;
                    }else{
                        $minPageNum = $currentPageNum - 1;
                        $maxPageNum = $currentPageNum + 1;
                    }
                    ?>
                    <li class="list-item <?php showPrevious($currentPageNum) ?>"><a href="<?php echo returnRequestUrlPage($_SERVER['REQUEST_URI']); ?><?php echo $currentPageNum - 1; ?>">前へ></a></li>
                    <?php
                    for($i = $minPageNum; $i <= $maxPageNum; $i++):
                        ?>
                        <li class="list-item <?php if($currentPageNum == $i ) echo 'active'; ?>"><a href="<?php echo returnRequestUrlPage($_SERVER['REQUEST_URI']); ?><?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php
                    endfor;
                    ?>
                    <li class="list-item <?php showNext($currentPageNum, $maxPageNum) ?>"><a href="<?php echo returnRequestUrlPage($_SERVER['REQUEST_URI']); ?><?php echo $currentPageNum + 1; ?>">次へ</a></li>
                </ul>
            <?php endif; ?>
        </div>

    </div>



</main>
<?php //require('footer.php'); ?>
</body>
</html>