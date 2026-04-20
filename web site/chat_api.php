<?php
session_start();

// 1. 권한 체크
if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['userid'];
$room = $_GET['room'] ?? 'default';

// 2. 경로 설정 (chat_data 폴더 내의 파일을 직접 가리키도록 수정)
$chatDir = __DIR__ . '/chat_data';
if (!is_dir($chatDir)) {
    mkdir($chatDir, 0777, true);
}

// 파일명 규칙 수정: 'chat_' 접두사를 제거하여 기존 eoullim.json과 매칭
$safeRoomName = preg_replace('/[^a-zA-Z0-9_]/', '', $room);
$chatFile = $chatDir . '/' . $safeRoomName . '.json'; 

header('Content-Type: application/json');

// --- POST: 메시지 저장 ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    $replyTo = $_POST['replyTo'] ?? null;

    if ($message === '') {
        echo json_encode(['success' => false]);
        exit;
    }

    $newMessage = [
        'id' => time() . rand(100, 999),
        'userId' => $userId,
        'message' => htmlspecialchars($message),
        'time' => date('H:i'),
        'replyTo' => $replyTo
    ];

    // 기존 메시지 불러오기
    $messages = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
    if (!is_array($messages)) $messages = [];

    $messages[] = $newMessage;
    
    // JSON 저장 (한글 깨짐 방지 및 권한 확인)
    file_put_contents($chatFile, json_encode($messages, JSON_UNESCAPED_UNICODE));

    echo json_encode(['success' => true]);

} 
// --- GET: 메시지 불러오기 ---
else {
    $messages = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
    if (!is_array($messages)) $messages = [];

    echo json_encode([
        'success' => true,
        'messages' => $messages,
        'currentUser' => $userId
    ]);
}