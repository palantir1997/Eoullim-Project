<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false]);
    exit;
}

$userId = $_SESSION['userid'];
$ip = $_SERVER['REMOTE_ADDR'];
$page = $_POST['page'] ?? 'Unknown';

$chatDir = __DIR__ . '/chat_data';
if (!is_dir($chatDir)) mkdir($chatDir, 0777, true);
$onlineFile = $chatDir . '/online_status.json';

// 1. 현재 접속 데이터 불러오기
$data = file_exists($onlineFile) ? json_decode(file_get_contents($onlineFile), true) : [];

// 2. 내 정보 업데이트
$data[$userId] = [
    'last_seen' => time(),
    'ip' => $ip,
    'page' => $page
];

// 3. 15초 이상 활동 없는 유저 제거 (오프라인 처리)
$activeUsers = [];
foreach ($data as $id => $info) {
    if (time() - $info['last_seen'] < 15) {
        $activeUsers[] = [
            'id' => $id,
            'ip' => $info['ip'],
            'page' => $info['page']
        ];
    } else {
        unset($data[$id]); // 데이터 정리
    }
}

// 4. 저장 및 응답
file_put_contents($onlineFile, json_encode($data));
echo json_encode(['success' => true, 'onlineUsers' => $activeUsers]);