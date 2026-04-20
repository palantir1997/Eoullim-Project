<?php
session_start();
header('Content-Type: application/json');

$dataFile = 'chat_data/chat_eoullim.json'; 

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'Guest';
$currentPage = isset($_POST['page']) ? $_POST['page'] : 'Unknown';
$userIp = $_SERVER['REMOTE_ADDR'];
$now = time();

// 1. 파일 읽기 (없으면 빈 배열 생성)
$allData = [];
if (file_exists($dataFile)) {
    $allData = json_decode(file_get_contents($dataFile), true) ?: [];
}

// 2. 'online_users' 섹션이 없으면 새로 만들기
if (!isset($allData['online_users'])) {
    $allData['online_users'] = [];
}

// 3. 현재 유저 정보 업데이트
$allData['online_users'][$userId] = [
    'id' => $userId,
    'page' => $currentPage,
    'ip' => $userIp,
    'last_seen' => $now
];

// 4. 30초 넘게 반응 없는 유저 정리
foreach ($allData['online_users'] as $id => $info) {
    if ($now - $info['last_seen'] > 30) {
        unset($allData['online_users'][$id]);
    }
}

// 5. 파일에 저장 (LOCK_EX로 데이터 꼬임 방지)
file_put_contents($dataFile, json_encode($allData), LOCK_EX);

// 6. 결과 반환
echo json_encode([
    'success' => true, 
    'onlineUsers' => array_values($allData['online_users'])
]);