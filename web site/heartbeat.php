<?php
session_start();
header('Content-Type: application/json');

$chatDir = 'chat_data';
if (!is_dir($chatDir)) mkdir($chatDir, 0777, true);
$statusFile = $chatDir . '/online_users.json';

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : 'Guest_' . substr(session_id(), 0, 5);
$userIp = $_SERVER['REMOTE_ADDR'];
// JS에서 보낸 page 값이 있으면 쓰고, 없으면 기본값 유지
$currentPage = (isset($_POST['page']) && !empty($_POST['page'])) ? $_POST['page'] : 'Admin Dashboard';
$action = isset($_POST['action']) ? $_POST['action'] : '';

$users = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];
$newUsersByMap = [];
$now = time();

foreach ($users as $user) {
    // 1. 로그아웃 요청이면 삭제
    if ($action === 'logout' && $user['id'] === $userId) continue;

    // 2. 현재 업데이트할 유저($userId)의 '과거 기록'은 여기서 미리 제외합니다.
    if ($user['id'] === $userId) continue;

    // 3. 로그인 전환 처리 (Guest 세션 정리)
    if (isset($_SESSION['userid']) && strpos($user['id'], 'Guest_') === 0 && $user['ip'] === $userIp) continue;

    // 4. 시간 초과(1시간) 체크
    if ($now - $user['last_seen'] < 3600) {
        $newUsersByMap[$user['id']] = $user;
    }
}

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