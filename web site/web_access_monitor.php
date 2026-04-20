<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>
        alert('회원만 이용할 수 있습니다. 로그인해주세요!');
        location.href = 'login.php';
    </script>";
    exit;
} 
$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulim Security - Web Access Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fc; }
        .sidebar { min-height: 100vh; background-color: #1c2331; }
        .sidebar .nav-link { color: rgba(255,255,255,.7); padding: 1rem 1.5rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: rgba(255,255,255,.1); }
        .sidebar .navbar-brand { color: #fff; font-weight: bold; padding: 1.5rem 1rem; text-align: center; display: block; text-decoration: none; }
        .content-wrapper { flex: 1; display: flex; flex-direction: column; }
        .topbar { background-color: #fff; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); }
        
        #log-display { 
            background-color: #000; 
            color: #00ff00; 
            font-family: 'Consolas', monospace; 
            padding: 20px; 
            height: 500px; 
            overflow-y: auto; 
            border-radius: 5px;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .log-entry { margin-bottom: 5px; border-bottom: 1px solid #222; }
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
            <li><a href="web_access_monitor.php" class="nav-link active"><i class="fas fa-fw fa-terminal me-2"></i> Web Monitor</a></li>
            <li><a href="security_logs.php" class="nav-link"><i class="fas fa-fw fa-user-shield me-2"></i> Security Logs</a></li>
            <li><a href="communication.php" class="nav-link"><i class="fas fa-fw fa-comments me-2"></i> Team Communication</a></li>
        </ul>
    </div>

    <div class="content-wrapper">
        <nav class="navbar topbar mb-4 px-4 py-3 d-flex justify-content-between align-items-center">
            <h3 class="h4 mb-0 text-gray-800 fw-bold">Real-time Web Access Monitor</h3>
            
            <ul class="navbar-nav align-items-center flex-row mb-0">
                <li class="nav-item d-flex align-items-center">
                    <span class="me-2 d-none d-lg-inline text-gray-600 small fw-bold">
                        <?php echo $userId ? htmlspecialchars($userId) : 'Guest'; ?> (Online)
                    </span>
                    <i class="fas fa-user-circle fa-2x text-gray-400 me-3"></i>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger fw-bold">Logout</a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid px-4">
            <div class="card shadow-sm border-0">
                <div class="card-header py-3 bg-dark d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-terminal me-2"></i>Apache Access Logs (Live)</h6>
                    <span class="badge bg-success">LIVE UPDATING</span>
                </div>
                <div class="card-body p-0">
                    <div id="log-display">
                        로그를 불러오는 중입니다...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- 1. 기존 로그 업데이트 기능 (유지) ---
        function updateLogs() {
            fetch('get_web_logs.php')
                .then(response => response.text())
                .then(data => {
                    const display = document.getElementById('log-display');
                    display.innerHTML = data;
                    display.scrollTop = display.scrollHeight;
                })
                .catch(err => {
                    document.getElementById('log-display').innerHTML = "데이터 호출 오류: " + err;
                });
        }
        setInterval(updateLogs, 5000);
        updateLogs();

        // --- 2. 실시간 팀 세션 업데이트
        
        // 현재 페이지가 web_access_monitor.php이므로 리스트에는 "Web Monitor"로 표시되도록 설정
        const pageId = "Web Monitor"; 

        async function updateLiveStatus() {
            try {
                const formData = new FormData();
                formData.append('page', pageId); // 서버로 "Web Monitor" 전달

                const response = await fetch('heartbeat.php', { method: 'POST', body: formData });
                // board.php와 동일하게 처리
                const result = await response.json();
            } catch (error) { 
                console.error("Status Sync Error:", error); 
            }
        }

        // 로그아웃 비컨 (브라우저 닫을 때 즉시 해제)
        window.addEventListener('beforeunload', function (e) {
            const formData = new FormData();
            formData.append('action', 'logout'); 
            navigator.sendBeacon('heartbeat.php', formData);
        });

        // 5초마다 신호 전송 시작
        setInterval(updateLiveStatus, 5000);
        updateLiveStatus();
    </script>
</body>
</html>