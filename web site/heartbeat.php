<?php
session_start();
header('Content-Type: application/json');

$jsonFile = 'chat_data/chat_eoullim.json';
$users = [];

if (file_exists($jsonFile)) {
    $content = file_get_contents($jsonFile);
    $users = json_decode($content, true) ?: [];
}

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

// 로그아웃 액션이 들어오거나 세션이 없으면 목록에서 제거
if (isset($_POST['action']) && $_POST['action'] === 'logout' && $userId) {
    unset($users[$userId]);
} 
// 평소에는 정보 업데이트
elseif ($userId) {
    $users[$userId] = [
        'id' => $userId,
        'page' => isset($_POST['page']) ? $_POST['page'] : 'Unknown',
        'ip' => $_SERVER['REMOTE_ADDR'],
        'last_seen' => time()
    ];
}

// 15초 이상 응답 없는 유저도 '안전장치'로 제거 (브라우저가 비정상 종료될 경우 대비)
$currentTime = time();
$activeUsers = array_filter($users, function($u) use ($currentTime) {
    return ($currentTime - $u['last_seen']) < 15;
});

file_put_contents($jsonFile, json_encode(array_values($activeUsers), JSON_UNESCAPED_UNICODE));

echo json_encode([
    'success' => true,
    'onlineUsers' => array_values($activeUsers)
]);