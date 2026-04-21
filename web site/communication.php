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

/* 메시지 묶음 컨테이너 */
.msg-group { display: flex; flex-direction: column; margin-bottom: 1.2rem; max-width: 75%; }
.msg-group.me { align-self: flex-end; align-items: flex-end; }
.msg-group.other { align-self: flex-start; align-items: flex-start; }

.chat-bubble {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    font-size: 0.95rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    position: relative;
    word-break: break-all;
}
.chat-bubble.other { background-color: #fff; border-bottom-left-radius: 0; }
.chat-bubble.me { background-color: #1c2331; color: #fff; border-bottom-right-radius: 0; }

/* 답장 인용구 디자인 (말풍선 내부 상단) */
.reply-quote {
    background: rgba(0, 0, 0, 0.05);
    border-left: 3px solid #6c757d;
    padding: 4px 8px;
    margin-bottom: 8px;
    font-size: 0.8rem;
    border-radius: 4px;
    color: #666;
}
.chat-bubble.me .reply-quote {
    background: rgba(255, 255, 255, 0.15);
    border-left-color: #adb5bd;
    color: #e9ecef;
}

.chat-sender { font-size: 0.75rem; font-weight: bold; color: #6c757d; margin-bottom: 0.25rem; }

/* 답장 버튼 (말풍선 바로 아래) */
.reply-btn {
    font-size: 0.7rem;
    cursor: pointer;
    color: #6c757d;
    text-decoration: none;
    margin-top: 4px;
    opacity: 0.7;
}
.reply-btn:hover { opacity: 1; color: #007bff; }
</style>
</head>

<body class="d-flex">

<div class="sidebar d-flex flex-column flex-shrink-0" style="width: 250px;">
    <a href="index.php" class="navbar-brand border-bottom border-secondary mb-3">
        <i class="fas fa-shield-alt me-2"></i>Aulim Security
    </a>
    <ul class="nav flex-column mb-auto">
        <li><a href="board.php" class="nav-link"><i class="fas fa-fw fa-table me-2"></i> Team Board</a></li>
        <li><a href="index.php" class="nav-link"><i class="fas fa-fw fa-tachometer-alt me-2"></i> Admin Dashboard</a></li>
        <li><a href="web_access_monitor.php" class="nav-link"><i class="fas fa-fw fa-terminal me-2"></i> Web Monitor</a></li>
        <li><a href="security_logs.php" class="nav-link"><i class="fas fa-fw fa-user-shield me-2"></i> Security Logs</a></li>
        <li><a href="communication.php" class="nav-link active"><i class="fas fa-fw fa-comments me-2"></i> Team Communication</a></li>
    </ul>
</div>

<div class="content-wrapper">
    <nav class="navbar topbar mb-4 px-4 py-3 d-flex justify-content-between align-items-center">
        <h3 class="h4 mb-0 text-gray-800 fw-bold">Team Communication</h3>
        <div>
            <?php echo htmlspecialchars($userId); ?> (Online)
            <a href="logout.php" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
        </div>
    </nav>

    <div class="container-fluid px-4 chat-container pb-4">
        <div class="card shadow-sm h-100 d-flex flex-column">
            <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><button class="nav-link active fw-bold">Eoullim Project</button></li>
                </ul>
            </div>

            <div class="chat-box" id="chatBox"></div>

            <div class="card-footer bg-white p-3">
                <div class="input-group">
                    <input type="text" id="chatInput" class="form-control bg-light border-0" placeholder="메시지를 입력하세요...">
                    <button id="sendBtn" class="btn btn-primary px-4">전송 <i class="fas fa-paper-plane ms-1"></i></button>
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
let selectedReplyId = null;

function findMessageById(messages, id) {
    return messages.find(m => String(m.id) === String(id));
}

const pageId = "Team Communication"; 

async function updateLiveStatus() {
    try {
        const formData = new FormData();
        formData.append('page', pageId); // 서버로 "Team Communication" 전달

        const response = await fetch('heartbeat.php', { method: 'POST', body: formData });
        const result = await response.json();
    } catch (error) { 
        console.error("Status Sync Error:", error); 
    }
}

async function loadMessages() {
    try {
        const res = await fetch(`chat_api.php?room=${ROOM}`);
        const data = await res.json();
        if (!data.success) return;

        chatBox.innerHTML = '';

        data.messages.forEach(msg => {
            const isMe = msg.userId === data.currentUser;

            // 메시지 그룹 생성 (이름 + 말풍선 + 답장버튼 묶음)
            const group = document.createElement('div');
            group.className = 'msg-group ' + (isMe ? 'me' : 'other');

            // 1. 이름
            const sender = document.createElement('div');
            sender.className = 'chat-sender';
            sender.innerText = isMe ? 'Me (' + msg.userId + ')' : msg.userId;

            // 2. 말풍선
            const bubble = document.createElement('div');
            bubble.className = 'chat-bubble ' + (isMe ? 'me' : 'other');

            // [핵심] 답장 인용구 표시
            if (msg.replyTo) {
            const originalMsg = findMessageById(data.messages, msg.replyTo);
            if (originalMsg) {
                const quote = document.createElement('div');
                quote.className = 'reply-quote';
                
                const quoteIcon = document.createElement('i');
                quoteIcon.className = 'fas fa-quote-left fa-xs me-1';
                
                const authorStrong = document.createElement('strong');
                authorStrong.textContent = originalMsg.userId;

                const msgPreview = document.createTextNode(`: ${originalMsg.message.substring(0, 20)}${originalMsg.message.length > 20 ? '...' : ''}`);

                quote.appendChild(quoteIcon);
                quote.appendChild(authorStrong);
                quote.appendChild(msgPreview);
                
                bubble.appendChild(quote);
            }
        }

            const textSpan = document.createElement('span');
            textSpan.innerText = msg.message;
            bubble.appendChild(textSpan);

            // 3. 답장 버튼 (말풍선 바로 아래)
            const replyBtn = document.createElement('div');
            replyBtn.className = 'reply-btn';
            replyBtn.innerHTML = '<i class="fas fa-reply me-1"></i>답장 달기';
            replyBtn.onclick = () => {
                selectedReplyId = msg.id;
                input.placeholder = `${msg.userId}님께 답장 중... (ESC로 취소)`;
                input.classList.add('border-primary');
                input.focus();
            };

            group.appendChild(sender);
            group.appendChild(bubble);
            group.appendChild(replyBtn);
            chatBox.appendChild(group);
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    } catch (err) { console.error(err); }
}

async function sendMessage() {
    const message = input.value.trim();
    if (!message) return;

    const formData = new FormData();
    formData.append('message', message);
    if (selectedReplyId) formData.append('replyTo', selectedReplyId);

    try {
        await fetch(`chat_api.php?room=${ROOM}`, { method: 'POST', body: formData });
        input.value = '';
        input.placeholder = "메시지를 입력하세요...";
        input.classList.remove('border-primary');
        selectedReplyId = null; 
        loadMessages();
    } catch (err) { console.error(err); }
}

button.addEventListener('click', sendMessage);
input.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(); });
input.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        selectedReplyId = null;
        input.placeholder = "메시지를 입력하세요...";
        input.classList.remove('border-primary');
    }
});

window.addEventListener('beforeunload', function (e) {
    const formData = new FormData();
    formData.append('action', 'logout'); 
    navigator.sendBeacon('heartbeat.php', formData);
});

window.addEventListener('beforeunload', function (e) {
    const formData = new FormData();
    formData.append('action', 'logout'); 
    navigator.sendBeacon('heartbeat.php', formData);
});

setInterval(loadMessages, 5000);
loadMessages();
setInterval(updateLiveStatus, 5000);
updateLiveStatus();
</script>
</body>
</html>