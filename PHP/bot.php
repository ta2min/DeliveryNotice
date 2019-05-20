<?php
 
$accessToken = 'v/Cr8dvXOX3gzvo+J8d45DMiOHnpR9ZY6XOoTpkQJWJYF6xYRX29JA5P5B7J4XIH79yG4IxYXFhQOUDpMYH4ld8IGVMMBl506306WJdkB4ESYl8BRq2n8VKPK9EUBM/SmHXrh3HSSqF326QGfbB/+wdB04t89/1O/w1cDnyilFU=';
 
require_once('./my_library/heroku_database.php');

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);
 
//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容
 
//メッセージタイプが「text」以外のときは何も返さず終了
if($message_type != "text") exit;
 
//最新5件の訪問時間を返信する
 if($message_text == '履歴') {
    $return_message_texts = array();
    $recent_visit_time_history = get_visit_time_data();
    sending_messages($accessToken, $replyToken, $message_type, $recent_visit_time_history);
 }
 else {
    $return_message_text = array( "「" . $message_text . "」じゃねーよｗｗｗ");
    sending_messages($accessToken, $replyToken, $message_type, $return_message_text);
 }
?>
<?php
//メッセージの送信
function sending_messages($accessToken, $replyToken, $message_type, $return_message_texts){
    //レスポンスフォーマット
    $response_format_texts = array();
    foreach($return_message_texts as $return_message_text) {
        $response_format_text = [
            "type" => $message_type,
            "text" => $return_message_text
        ];
        array_push($response_format_texts, $response_format_text);
    };
    
 
    //ポストデータ
    $post_data = [
        "replyToken" => $replyToken,
        "messages" => $response_format_texts
    ];
 
    //curl実行
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer ' . $accessToken
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>