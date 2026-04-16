<?php
session_start();

// 로그인 확인
if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['userid'];
$room = isset($_GET['room']) ? $_GET['room'] : 'default';

// 채팅 데이터 저장 디렉토리
$chatDir = '/tmp/aulim_chat';
if (!is_dir($chatDir)) {
    mkdir($chatDir, 0777, true);
}

$chatFile = $chatDir . '/chat_' . preg_replace('/[^a-zA-Z0-9_]/', '', $room) . '.json';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ============ 메시지 저장 ============
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    if (empty($message)) {
        http_response_code(400);
        echo json_encode(['error' => 'Empty message']);
        exit;
    }
    
    // 메시지 데이터 구조
    $newMessage = [
        'id' => uniqid(),
        'userId' => $userId,
        'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        'timestamp' => date('Y-m-d H:i:s'),
        'time' => date('H:i'),
    ];
    
    // 기존 메시지 읽기
    $messages = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
    if (!is_array($messages)) {
        $messages = [];
    }
    
    // 새 메시지 추가
    $messages[] = $newMessage;
    
    // 최근 100개만 유지 (오래된 것은 삭제)
    if (count($messages) > 100) {
        $messages = array_slice($messages, -100);
    }
    
    // 파일에 저장
    file_put_contents($chatFile, json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    
    echo json_encode([
        'success' => true,
        'message' => $newMessage
    ]);

} else {
    // ============ 메시지 조회 ============
    $messages = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
    
    if (!is_array($messages)) {
        $messages = [];
    }
    
    // 최근 50개만 반환
    $messages = array_slice($messages, -50);
    
    echo json_encode([
        'success' => true,
        'messages' => $messages,
        'currentUser' => $userId
    ]);
}
?>