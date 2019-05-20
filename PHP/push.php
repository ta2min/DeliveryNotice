
<?php
require_once('./my_library/heroku_database.php');
// HTTPヘッダを設定
$channelToken = 'v/Cr8dvXOX3gzvo+J8d45DMiOHnpR9ZY6XOoTpkQJWJYF6xYRX29JA5P5B7J4XIH79yG4IxYXFhQOUDpMYH4ld8IGVMMBl506306WJdkB4ESYl8BRq2n8VKPK9EUBM/SmHXrh3HSSqF326QGfbB/+wdB04t89/1O/w1cDnyilFU=';
$headers = [
	'Authorization: Bearer ' . $channelToken,
	'Content-Type: application/json; charset=utf-8',
];
//データベースにデータを入れる
insert_data();
// POSTデータを設定してJSONにエンコード
$post = [
	'to' => 'U7bf92432cd2259bb6f33c36f427a0f88',
	'messages' => [
		[
			'type' => 'text',
			'text' => 'だれかきました',
		],
	],
];
$post = json_encode($post);

// HTTPリクエストを設定
$ch = curl_init('https://api.line.me/v2/bot/message/push');
$options = [
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_HTTPHEADER => $headers,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_BINARYTRANSFER => true,
	CURLOPT_HEADER => true,
	CURLOPT_POSTFIELDS => $post,
];
curl_setopt_array($ch, $options);

// 実行
$result = curl_exec($ch);

// エラーチェック
$errno = curl_errno($ch);
if ($errno) {
	return;
}

// HTTPステータスを取得
$info = curl_getinfo($ch);
$httpStatus = $info['http_code'];

$responseHeaderSize = $info['header_size'];
$body = substr($result, $responseHeaderSize);

// 200 だったら OK
echo $httpStatus . ' ' . $body;