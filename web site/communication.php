<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>
        alert('회원 전용 채널입니다. 로그인 후 입장해주세요!');
        location.href = 'login.php';
    </script>";
    exit;
}
$userId = $_SESSION['userid'];
?> 
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aulim Security Portal - Team Communication</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<style>
body { background-color: #f8f9fc; }
.sidebar { min-height: 100vh; background-color: #1c2331; }
.sidebar .nav-link { color: rgba(255,255,255,.7); padding: 1rem 1.5rem; }
.sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: rgba(255,255,255,.1); }
.sidebar .navbar-brand { color: #fff; font-weight: bold; padding: 1.5rem 1rem; text-align: center; display: block; text-decoration: none; }

.content-wrapper { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
.topbar { background-color: #fff; box-shadow: 0 .15rem 1.75rem rgba(58,59,69,.15); }

.chat-container { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.chat-box { flex: 1; overflow-y: auto; background-color: #f1f3f9; padding: 1.5rem; display: flex; flex-direction: column; }

.chat-bubble {
    max-width: 75%;
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}
.chat-bubble.other {
    background-color: #fff;
    border-bottom-left-radius: 0;
    align-self: flex-start;
}
.chat-bubble.me {
    background-color: #1c2331;
    color: #fff;
    border-bottom-right-radius: 0;
    align-self: flex-end;
}

.chat-sender {
    font-size: 0.75rem;
    font-weight: bold;
    color: #6c757d;
    margin-bottom: 0.25rem;
}
.chat-sender.me {
    text-align: right;
}
</style>
</head>

<body class="d-flex">

<!-- 사이드바 -->
<!-- <div class="sidebar d-flex flex-column flex-shrink-0" style="width:250px;">
    <a href="index.php" class="navbar-brand border-bottom border-secondary mb-3">
        <i class="fas fa-shield-alt me-2"></i>Aulim Security
    </a>

    <ul class="nav flex-column mb-auto">
        <li><a href="board.php" class="nav-link">Team Board</a></li>
        <li><a href="index.php" class="nav-link">Admin Dashboard</a></li>
        <li><a href="web_access_monitor.php" class="nav-link">Web Monitor</a></li>
        <li><a href="security_logs.php" class="nav-link">Security Logs</a></li>
        <li><a href="communication.php" class="nav-link active">Team Communication</a></li>
    </ul>

    
</div> -->

    <div class="sidebar d-flex flex-column flex-shrink-0" style="width: 250px;">
    <a href="index.php" class="navbar-brand border-bottom border-secondary mb-3">
        <i class="fas fa-shield-alt me-2"></i>Aulim Security
    </a>
    <ul class="nav flex-column mb-auto">
        <li>
            <a href="board.php" class="nav-link">
                <i class="fas fa-fw fa-table me-2"></i> Team Board
            </a>
        </li>
        <li>
            <a href="index.php" class="nav-link">
                <i class="fas fa-fw fa-tachometer-alt me-2"></i> Admin Dashboard
            </a>
        </li>
        <li>
            <a href="web_access_monitor.php" class="nav-link">
                <i class="fas fa-fw fa-terminal me-2"></i> Web Monitor
            </a>
        </li>
        <li>
            <a href="security_logs.php" class="nav-link">
                <i class="fas fa-fw fa-user-shield me-2"></i> Security Logs
            </a>
        </li>
        <li>
            <a href="communication.php" class="nav-link active">
                <i class="fas fa-fw fa-comments me-2"></i> Team Communication
            </a>
        </li>
    </ul>
</div>

<!-- 메인 -->
<div class="content-wrapper">

<!-- 상단 -->
<nav class="navbar topbar mb-4 px-4 py-3 d-flex justify-content-between align-items-center">
    <!--<div class="fw-bold">Team Communication</div>-->
    <h3 class="h4 mb-0 text-gray-800 fw-bold">Team Communication</h3>

    <div>
        <?php echo htmlspecialchars($userId); ?> (Online)
        <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
    </div>
</nav>

<div class="container-fluid px-4 chat-container pb-4">

<div class="card shadow-sm h-100 d-flex flex-column">

<!-- 탭 -->
<div class="card-header bg-white pt-3 pb-0 border-bottom-0">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active fw-bold">Eoullim Project</button>
        </li>
    </ul>
</div>

<!-- 채팅 영역 -->
<div class="chat-box" id="chatBox"></div>

<!-- 입력 -->
<div class="card-footer bg-white p-3">
    <div class="input-group">
        <input type="text" id="chatInput" class="form-control bg-light border-0" placeholder="메시지를 입력하세요...">
        <button id="sendBtn" class="btn btn-primary px-4">
            전송 <i class="fas fa-paper-plane ms-1"></i>
        </button>
    </div>
</div>

</div>
</div>
</div>

<script>
const chatBox = document.getElementById('chatBox');
const input = document.getElementById('chatInput');
const button = document.getElementById('sendBtn');

const ROOM = "eoullim";

// ========================
// 메시지 불러오기
// ========================
async function loadMessages() {
    try {
        const res = await fetch(`chat_api.php?room=${ROOM}`);
        const data = await res.json();

        if (!data.success) return;

        chatBox.innerHTML = '';

        data.messages.forEach(msg => {
            const isMe = msg.userId === data.currentUser;

            const sender = document.createElement('div');
            sender.className = 'chat-sender ' + (isMe ? 'me mt-3' : 'mt-2');
            sender.innerText = isMe ? 'Me (' + msg.userId + ')' : msg.userId;

            const bubble = document.createElement('div');
            bubble.className = 'chat-bubble ' + (isMe ? 'me' : 'other');
            bubble.innerText = msg.message;

            chatBox.appendChild(sender);
            chatBox.appendChild(bubble);
        });

        setTimeout(() => {
            chatBox.scrollTop = chatBox.scrollHeight;
        }, 100);

    } catch (err) {
        console.error(err);
    }
}

// ========================
// 메시지 보내기
// ========================
async function sendMessage() {
    const message = input.value.trim();
    if (!message) return;

    const formData = new FormData();
    formData.append('message', message);

    try {
        await fetch(`chat_api.php?room=${ROOM}`, {
            method: 'POST',
            body: formData
        });

        input.value = '';
        loadMessages();

    } catch (err) {
        console.error(err);
    }
}

// 이벤트
button.addEventListener('click', sendMessage);

input.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
});

// 자동 갱신
setInterval(loadMessages, 2000);

// 최초 실행
loadMessages();
</script>

</body>
</html>