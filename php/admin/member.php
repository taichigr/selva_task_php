<?php
// adminトップ画面
require('../function.php');
require('auth.php');


try {
    $results = getAllUsers();
} catch (Exception $e) {
    $err_msg['common'] = MSG09;
}

if(!empty($_GET)) {
    print_r($_GET);
    $id = $_GET['id'];
    !empty($_GET['male'])? $male = $_GET['male']: $male = '';
    !empty($_GET['female'])? $female = $_GET['female']: $female = '';
    $pref_name = $_GET['pref_name'];
    !empty($_GET['free'])? $free = "%".$_GET['free']."%": $free = '';
    if(!empty($_GET['orderById'])) $orderById = $_GET['orderById'];
    if(!empty($_GET['orderByCreatedAt'])) $orderByCreatedAt = $_GET['orderByCreatedAt'];

    print_r($_GET);
//    echo 'orderById:'.$orderById;
    if(empty($id) && $pref_name === "0" && empty($free) && empty($male) && empty($female)) {
        // TODO ここで何も検索項目が入力されていなくてもID、created_atの降順昇順がクリックされたら頑張る
        $dbh = dbConnect();
        $sql = 'SELECT id, name_sei, name_mei, gender, pref_name, address, created_at 
                    FROM members 
            ';
        if(!empty($orderById)){
            if($orderById === "desc") {
                $sql .= ' ORDER BY id DESC';
                $orderById = "asc";
            } else {
                $sql .= ' ORDER BY id ASC';
                $orderById = "desc";
            }
        }
        if(!empty($orderByCreatedAt)) {
            if($orderByCreatedAt === "desc") {
                $sql .= ' ORDER BY created_at DESC';
                $orderByCreatedAt = "asc";
            } else {
                $sql .= ' ORDER BY created_at ASC';
                $orderByCreatedAt = "desc";
            }
        }
        $data = array(
        );
        $stmt = queryPost($dbh, $sql, $data);
        $results = $stmt->fetchAll();
    } else {
            try {
                $dbh = dbConnect();
                $sql = 'SELECT id, name_sei, name_mei, gender, pref_name, address, created_at FROM members WHERE';
                // $idがあるときの処理
                if(!empty($id)) {
                    $sql .= ' id = :id';
                    if(!empty($male) && !empty($female)) {
                        $sql .= ' AND gender = :male OR gender = :female';
                    } elseif(!empty($male) && empty($female)) {
                        $sql .= ' AND gender = :male';
                    } elseif(!empty($female) && empty($male)) {
                        $sql .= ' AND gender = :female';
                    }
                    if($pref_name !== "0") {
                        $sql .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sql .= ' AND name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free';
                    }
                }
                // $idがなくて、性別があるときの処理
                if(empty($id) && !empty($male) && !empty($female)) {
                    $sql .= ' gender = :male OR gender = :female';
                    if($pref_name !== "0") {
                        $sql .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sql .= ' AND name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free';
                    }
                } elseif(empty($id) && !empty($male) && empty($female)) {
                    $sql .= ' gender = :male';
                    if($pref_name !== "0") {
                        $sql .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sql .= ' AND name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free';
                    }
                } elseif(empty($id) && !empty($female) && empty($male)) {
                    $sql .= ' gender = :female';
                    if($pref_name !== "0") {
                        $sql .= ' AND pref_name = :pref_name';
                    }
                    if(!empty($free)) {
                        $sql .= ' AND name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :free';
                    }
                }
                // $id,性別がないときの処理
                if(empty($id) && empty($male) && empty($female) && $pref_name !== "0") {
                    $sql .= ' pref_name = :pref_name';
                    if(!empty($free)) {
                        $sql .= ' AND name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :email';
                    }
                }
                // $id, 性別、$pref_nameがないときの処理
                if(empty($id) && empty($male) && empty($female) && $pref_name === "0" && !empty($free)) {
                    $sql .= ' name_sei LIKE :free OR name_mei LIKE :free OR email LIKE :email';
                }
                // 昇順降順
                if(!empty($orderById)){
                    if($orderById === "desc") {
                        $sql .= ' ORDER BY id DESC';
                        $orderById = "asc";
                    } else {
                        $sql .= ' ORDER BY id ASC';
                        $orderById = "desc";
                    }
                }
                if(!empty($orderByCreatedAt)) {
                    if($orderByCreatedAt === "desc") {
                        $sql .= ' ORDER BY created_at DESC';
                        $orderByCreatedAt = "asc";
                    } else {
                        $sql .= ' ORDER BY created_at ASC';
                        $orderByCreatedAt = "desc";
                    }
                }

                // ' ORDER BY created_at ASC'
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

                $stmt = queryPost($dbh, $sql, $data);
                $results = $stmt->fetchAll();
                print_r($results);
            } catch (Exception $e) {
                $err_msg['common'] = MSG09;
                print_r($err_msg);
            }
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
    <link rel="stylesheet" href="../../css/style.css">
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
        <div class="member-form-container">
            <form id="member-search" action="member.php" method="get">
                <table class="member-table">
                    <tr>
                        <td class="member-table-left">ID</td>
                        <td class="member-table-right"><input type="text" name="id"></td>
                    </tr>
                    <tr>
                        <td class="member-table-left">性別</td>
                        <td class="member-table-right">
                            <div class="form-inline">
                                <label><input type="checkbox" name="male" value="1" <?php if(!empty($male) && $male === "1") echo "checked" ?>>男性</label>
                            </div>
                            <div class="form-inline">
                                <label><input type="checkbox" name="female" value="2" <?php if(!empty($female) && $female === "2") echo "checked" ?>>女性</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="member-table-left">都道府県</td>
                        <td class="member-table-right">
                            <select name="pref_name">
                                <option value="0">選択してください</option>
                                <?php foreach($prefectures as $prefecture): ?>
                                    <option value="<?php echo $prefecture ?>" <?php if(!empty($prefName) && $prefecture === $prefName) echo 'selected'; ?>><?php echo $prefecture ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="member-table-left">フリーワード</td>
                        <td class="member-table-right"><input type="text" name="free"></td>
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
                        <button name="orderById" value="<?php echo !empty($orderById)? $orderById: $orderById = "desc" ?>" class="submit-order" type="submit" form="member-search">▼</button>
                    </th>
                    <th>氏名</th>
                    <th>性別</th>
                    <th>住所</th>
                    <th>
                        登録日時
                        <button name="orderByCreatedAt" value="<?php echo !empty($orderByCreatedAt)? $orderByCreatedAt: $orderByCreatedAt = "desc" ?>" class="submit-order" type="submit" form="member-search">▼</button>
                    </th>
                </tr>
                <?php if(!empty($results)): ?>
                    <?php foreach ($results as $result): ?>
                        <tr class="member-showtable-body">
                            <td><?php echo $result['id'] ?></td>
                            <td><?php echo $result['name_sei'] ?>　<?php echo $result['name_mei'] ?></td>
                            <td><?php echo showGender($result['gender']) ?></td>
                            <td><?php echo $result['pref_name'].$result['address'] ?></td>
                            <td><?php echo date('Y/m/d', strtotime($result['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>
</main>
<?php //require('footer.php'); ?>
</body>
</html>