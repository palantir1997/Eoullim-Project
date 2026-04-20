<?php
session_start();
header('Content-Type: application/json');

$dataFile = 'chat_data/chat_eoullim.json'; 

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'Guest';
$currentPage = isset($_POST['page']) ? $_POST['page'] : 'Unknown';
$userIp = $_SERVER['REMOTE_ADDR'];
$now = time();

$allData = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $allData = json_decode($content, true) ?: [];
}

// 중요: 기존 파일이 순수 배열(채팅 전용)이었다면 객체로 강제 변환
if (!is_array($allData) || (isset($allData[0]) && !isset($allData['online_users']))) {
    $tempMessages = $allData; // 기존 채팅 메시지 백업
    $allData = ['messages' => $tempMessages, 'online_users' => []];
}

if (!isset($allData['online_users'])) {
    $allData['online_users'] = [];
}

// 유저 정보 갱신
$allData['online_users'][$userId] = [
    'id' => $userId,
    'page' => $currentPage,
    'ip' => $userIp,
    'last_seen' => $now
];

// 만료된 세션 정리
foreach ($allData['online_users'] as $id => $info) {
    if ($now - $info['last_seen'] > 30) {
        unset($allData['online_users'][$id]);
    }
}

file_put_contents($dataFile, json_encode($allData), LOCK_EX);

echo json_encode([
    'success' => true, 
    'onlineUsers' => array_values($allData['online_users'])
]);