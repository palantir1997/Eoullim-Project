<?php
session_start();
header('Content-Type: application/json');

$chatDir = 'chat_data';
if (!is_dir($chatDir)) mkdir($chatDir, 0777, true);
$statusFile = $chatDir . '/online_users.json';

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'Guest_' . substr(session_id(), 0, 5);
$userIp = $_SERVER['REMOTE_ADDR'];
$currentPage = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 'Admin Dashboard';
$action = isset($_POST['action']) ? $_POST['action'] : '';

$users = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];
$newUsersByMap = [];
$now = time();

// 기존 데이터 정리
foreach ($users as $user) {
    // 1. [로그아웃 처리] action이 logout이면 해당 유저는 건너뜀 (삭제)
    if ($action === 'logout' && $user['id'] === $userId) continue;

    // 2. [로그인 전환 처리] Guest에서 로그인한 경우, 동일 IP의 이전 Guest 기록 삭제
    if (isset($_SESSION['userid']) && strpos($user['id'], 'Guest_') === 0 && $user['ip'] === $userIp) continue;

    // 3. 시간 초과(1시간) 체크
    if ($now - $user['last_seen'] < 3600) {
        $newUsersByMap[$user['id']] = $user;
    }
}

// 로그아웃이 아닐 때만 현재 정보 업데이트
if ($action !== 'logout') {
    $newUsersByMap[$userId] = [
        'id' => $userId, 
        'ip' => $userIp, 
        'page' => $currentPage, 
        'last_seen' => $now
    ];
}

$finalUsers = array_values($newUsersByMap);
file_put_contents($statusFile, json_encode($finalUsers));
echo json_encode(['success' => true, 'onlineUsers' => $finalUsers]);