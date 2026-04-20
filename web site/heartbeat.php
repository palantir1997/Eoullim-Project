<?php
session_start();
header('Content-Type: application/json');

$chatDir = 'chat_data';
if (!is_dir($chatDir)) mkdir($chatDir, 0777, true);
$statusFile = $chatDir . '/online_users.json';

// 유저 ID 설정 (로그인 안했으면 Guest_ID로 표시)
$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'Guest_' . substr(session_id(), 0, 5);
$userIp = $_SERVER['REMOTE_ADDR'];
$currentPage = isset($_POST['page']) ? $_POST['page'] : 'Unknown';

$users = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];
$now = time();

// 현재 유저 정보 업데이트 또는 신규 등록
$users[$userId] = [
    'id' => $userId, 
    'ip' => $userIp, 
    'page' => $currentPage, 
    'last_seen' => $now
];

// [수정] 자동 삭제 기준을 1시간(3600초)으로 변경하여 넉넉하게 유지
foreach ($users as $id => $data) {
    if ($now - $data['last_seen'] > 3600) { 
        unset($users[$id]);
    }
}

file_put_contents($statusFile, json_encode(array_values($users)));
echo json_encode(['success' => true, 'onlineUsers' => array_values($users)]);