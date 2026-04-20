<?php
session_start();
header('Content-Type: application/json');

$jsonFile = 'chat_data/chat_eoullim.json';
$users = [];

// 1. 기존 데이터 읽기 (연관 배열로 읽어와야 중복 체크가 쉽습니다)
if (file_exists($jsonFile)) {
    $content = file_get_contents($jsonFile);
    $data = json_decode($content, true) ?: [];
    // ID를 키값으로 하는 배열로 변환 (이미 변환되어 있다면 그대로 사용)
    foreach ($data as $u) {
        if (isset($u['id'])) { $users[$u['id']] = $u; }
    }
}

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

// 2. 로그아웃 액션 처리
if (isset($_POST['action']) && $_POST['action'] === 'logout' && $userId) {
    unset($users[$userId]);
} 
// 3. 접속 정보 업데이트 (동일 ID가 있으면 덮어씌워져서 중복이 안 생깁니다)
elseif ($userId) {
    $users[$userId] = [
        'id' => $userId,
        'page' => isset($_POST['page']) ? $_POST['page'] : 'Unknown',
        'ip' => $_SERVER['REMOTE_ADDR'],
        'last_seen' => time()
    ];
}

// 4. 안전장치: 15초 이상 응답 없는 유저 제거
$currentTime = time();
$activeUsers = array_filter($users, function($u) use ($currentTime) {
    return ($currentTime - $u['last_seen']) < 15;
});

// 5. 저장 (JSON으로 저장할 때는 다시 순차 배열로 변환)
file_put_contents($jsonFile, json_encode(array_values($activeUsers), JSON_UNESCAPED_UNICODE));

// 6. 결과 반환
echo json_encode([
    'success' => true,
    'onlineUsers' => array_values($activeUsers)
]);