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
        .topbar { background-color: #fff; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); z-index: 10; }

        /* 채팅창 전용 스타일 */
        .chat-container { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .chat-box { flex: 1; overflow-y: auto; background-color: #f1f3f9; padding: 1.5rem; display: flex; flex-direction: column; }
        .chat-bubble { max-width: 75%; padding: 0.75rem 1rem; border-radius: 1rem; margin-bottom: 0.5rem; font-size: 0.95rem; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .chat-bubble.other { background-color: #fff; color: #333; border-bottom-left-radius: 0; align-self: flex-start; }
        .chat-bubble.me { background-color: #1c2331; color: #fff; border-bottom-right-radius: 0; align-self: flex-end; }
        .chat-sender { font-size: 0.75rem; font-weight: bold; color: #6c757d; margin-bottom: 0.25rem; }
        .chat-sender.me { text-align: right; }
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
            <li><a href="communication.php" class="nav-link active"><i class="fas fa-fw fa-comments me-2"></i> Team Communication</a></li>
        </ul>
    </div>

    <div class="content-wrapper">
        <nav class="navbar topbar mb-4 px-4 py-3 d-flex justify-content-between align-items-center">
            <form class="d-none d-sm-inline-block form-inline mr-auto" style="width: 350px;">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for...">
                    <button class="btn btn-primary" type="button"><i class="fas fa-search fa-sm"></i></button>
                </div>
            </form>
            <ul class="navbar-nav align-items-center flex-row mb-0">
                <li class="nav-item d-flex align-items-center">
                    <span class="me-2 d-none d-lg-inline text-gray-600 small fw-bold">
                        <?php echo htmlspecialchars($userId); ?> (Online)
                    </span>
                    <i class="fas fa-user-circle fa-2x text-gray-400 me-3"></i>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger fw-bold">Logout</a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid px-4 chat-container pb-4">
            <h3 class="h4 mb-4 text-gray-800 fw-bold">Team Communication</h3>

            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header bg-white pt-3 pb-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="chatTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="user-tab" data-bs-toggle="tab" data-bs-target="#user-pane" type="button" role="tab">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold" id="leader-tab" data-bs-toggle="tab" data-bs-target="#leader-pane" type="button" role="tab">Eoullim Project</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content flex-grow-1 d-flex flex-column" id="myTabContent">
                    <div class="tab-pane fade" id="user-pane" role="tabpanel">
                        <div class="p-4 text-center text-muted">General channel is currently quiet.</div>
                    </div>

                    <div class="tab-pane fade show active flex-grow-1 d-flex flex-column" id="leader-pane" role="tabpanel">
                        <div class="chat-box">
                            <div class="chat-sender ms-1">Leader</div>
                            <div class="chat-bubble other">Theme is set for SME. Let's define user roles.</div>

                            <div class="chat-sender ms-1 mt-2">Gisu (Infra Lead)</div>
                            <div class="chat-bubble other">Web팀 Mockup 준비되면 인프라 보안 설정 들어갈게요.</div>

                            <div class="chat-sender me me-1 mt-3">Me (<?php echo htmlspecialchars($userId); ?>)</div>
                            <div class="chat-bubble me">방금 보드에 Mockup 업로드했습니다. 확인 부탁드려요!</div>
                        </div>

                        <div class="card-footer bg-white p-3">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0" placeholder="메시지를 입력하세요...">
                                <button class="btn btn-primary px-4" type="button">전송 <i class="fas fa-paper-plane ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>