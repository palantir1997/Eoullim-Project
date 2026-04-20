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

// 기존 채팅 데이터와 섞이지 않도록 구조 강제 변환
if (!is_array($allData) || (isset($allData[0]) && !isset($allData['online_users']))) {
    $tempMessages = $allData;
    $allData = ['messages' => $tempMessages, 'online_users' => []];
}

if (!isset($allData['online_users'])) {
    $allData['online_users'] = [];
}

// 현재 접속 정보 갱신
$allData['online_users'][$userId] = [
    'id' => $userId,
    'page' => $currentPage,
    'ip' => $userIp,
    'last_seen' => $now
];

// 30초 이상 무응답 유저 삭제
foreach ($allData['online_users'] as $id => $info) {
    if ($now - $info['last_seen'] > 30) {
        unset($allData['online_users'][$id]);
    }
}

file_put_contents($dataFile, json_encode($allData), LOCK_EX);

// 중요: 여기서는 오직 JSON만 출력해야 합니다.
echo json_encode([
    'success' => true, 
    'onlineUsers' => array_values($allData['online_users'])
]);
exit; // 이후에 다른 내용이 출력되지 않도록 종료