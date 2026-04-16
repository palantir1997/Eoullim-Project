<?php
session_start();

if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['userid'];
$room = $_GET['room'] ?? 'default';

$chatDir = __DIR__ . '/chat_data';
if (!is_dir($chatDir)) mkdir($chatDir, 0777, true);

$chatFile = $chatDir . '/chat_' . preg_replace('/[^a-zA-Z0-9_]/', '', $room) . '.json';

header('Content-Type: application/json');

// POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');

    if ($message === '') {
        echo json_encode(['success' => false]);
        exit;
    }

    $newMessage = [
        'userId' => $userId,
        'message' => htmlspecialchars($message),
        'time' => date('H:i')
    ];

    $messages = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
    if (!is_array($messages)) $messages = [];

    $messages[] = $newMessage;

    file_put_contents($chatFile, json_encode($messages));

    echo json_encode(['success' => true]);

// GET
} else {
    $messages = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
    if (!is_array($messages)) $messages = [];

    echo json_encode([
        'success' => true,
        'messages' => $messages,
        'currentUser' => $userId
    ]);
}