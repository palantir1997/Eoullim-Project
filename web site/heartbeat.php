<?php
session_start();
header('Content-Type: application/json');

$chatDir = 'chat_data';
if (!is_dir($chatDir)) mkdir($chatDir, 0777, true);
$statusFile = $chatDir . '/online_users.json';

// 1. 유저 ID 및 IP 설정
$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'Guest_' . substr(session_id(), 0, 5);
$userIp = $_SERVER['REMOTE_ADDR'];

// 2. [수정] Unknown 방지: 페이지 정보가 없으면 기본값 설정
$currentPage = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 'Admin Dashboard';

$users = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];
$now = time();

// 3. [핵심 수정] 중복 검사 로직: 동일한 유저 ID가 있으면 해당 데이터만 업데이트 (중복 생성 방지)
$newUsers = [];
$found = false;

foreach ($users as $user) {
    if ($user['id'] === $userId) {
        // 이미 목록에 있는 유저라면 현재 정보로 갱신
        $newUsers[] = [
            'id' => $userId, 
            'ip' => $userIp, 
            'page' => $currentPage, 
            'last_seen' => $now
        ];
        $found = true;
    } else {
        // 다른 유저들은 1시간(3600초) 이내 활동 기록이 있는 경우에만 유지
        if ($now - $user['last_seen'] < 3600) {
            $newUsers[] = $user;
        }
    }
}

// 목록에 없던 새로운 유저라면 추가
if (!$found) {
    $newUsers[] = [
        'id' => $userId, 
        'ip' => $userIp, 
        'page' => $currentPage, 
        'last_seen' => $now
    ];
}

// 4. 결과 저장 및 출력
file_put_contents($statusFile, json_encode($newUsers));
echo json_encode(['success' => true, 'onlineUsers' => $newUsers]);