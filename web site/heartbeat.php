<?php
session_start();
header('Content-Type: application/json');

$chatDir = 'chat_data';
if (!is_dir($chatDir)) mkdir($chatDir, 0777, true);
$statusFile = $chatDir . '/online_users.json';

// 1. 유저 ID 및 IP 설정
$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'Guest_' . substr(session_id(), 0, 5);
$userIp = $_SERVER['REMOTE_ADDR'];
$currentPage = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 'Admin Dashboard';

// 2. 기존 데이터 로드 (ID를 키값으로 하는 연관 배열로 변환 준비)
$users = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];
$newUsersByMap = [];
$now = time();

// 3. [핵심] 기존 데이터를 정리하며 1시간 이내 활동자만 필터링
foreach ($users as $user) {
    // 1시간 이내 활동했고, 아직 이 ID가 맵에 등록되지 않았을 때만 유지
    if (($now - $user['last_seen'] < 3600) && !isset($newUsersByMap[$user['id']])) {
        $newUsersByMap[$user['id']] = $user;
    }
}

// 4. [중복 방지 핵심] 현재 접속한 유저 정보를 'ID 키'에 강제로 덮어씌움 (무조건 1개만 존재)
$newUsersByMap[$userId] = [
    'id' => $userId, 
    'ip' => $userIp, 
    'page' => $currentPage, 
    'last_seen' => $now
];

// 5. 저장 (JSON 저장을 위해 다시 인덱스 배열로 변환)
$finalUsers = array_values($newUsersByMap);
file_put_contents($statusFile, json_encode($finalUsers));

// 6. 출력
echo json_encode(['success' => true, 'onlineUsers' => $finalUsers]);