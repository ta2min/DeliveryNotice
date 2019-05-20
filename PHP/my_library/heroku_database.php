<?php
function connect_db() {
    //データベースのURLを取得
    $url = parse_url(getenv('DATABASE_URL'));
    $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
    //接続
    $pdo = new PDO($dsn, $url['user'], $url['pass']); 
    return $pdo;
}
function get_visit_time_data() {
    //データベースに接続
    $pdo = connect_db();
    // 最新5件の訪問履歴を取得するSQL
    $sql = 'SELECT * FROM delivery ORDER BY VisitTime DESC LIMIT 5';
    // SQLを実行して配列に型変換
    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    $time_data = array();
        // 日付のフォーマットを変更する
        foreach($rows as  $row) {
            $date = explode('.', $row['visittime'])[0];
            $date = preg_replace('/[^0-9]/', '', $date);
            $date = date('m月d日 H:i',strtotime($date));
            array_push($time_data, $date);
        };
    $dbh = null;
    // 週順に並び替え
    asort($time_data);
    return $time_data;
}

function insert_data() {
    $pdo = connect_db();
    // CURRENT_TIMESTAMPで現在時刻を取得(Postgresの関数)
    $sql = "INSERT INTO Delivery (VisitTime) VALUES(CURRENT_TIMESTAMP)";
    $pdo->query($sql);
    $pdo = null;
}
?>