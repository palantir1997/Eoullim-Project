<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('권한이 없습니다. 로그인 후 이용해주세요.'); location.href='login.php';</script>";
    exit;
}
$userId = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulim Security - IDS Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fc; }
        .sidebar { min-height: 100vh; background-color: #1c2331; }
        .sidebar .nav-link { color: rgba(255,255,255,.7); padding: 1rem 1.5rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: rgba(255,255,255,.1); }
        .sidebar .navbar-brand { color: #fff; font-weight: bold; padding: 1.5rem 1rem; text-align: center; display: block; text-decoration: none; }
        .content-wrapper { flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background-color: #fff; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); z-index: 10; }
        .log-card { border-radius: 1rem; border: none; box-shadow: 0 0.5rem 2rem rgba(0,0,0,0.1); }
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
            <li></li><a href="web_access_monitor.php" class="nav-link"><i class="fas fa-fw fa-terminal me-2"></i> Web Monitor</a></li>
            <li><a href="security_logs.php" class="nav-link active"><i class="fas fa-fw fa-user-shield me-2"></i> Security Logs</a></li>
            <li><a href="communication.php" class="nav-link"><i class="fas fa-fw fa-comments me-2"></i> Team Communication</a></li>
        </ul>
    </div>

    <div class="content-wrapper">
        <nav class="navbar topbar mb-4 px-4 py-3 d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0 text-gray-800 fw-bold">Security Monitoring</h1>
            <div class="user-info d-flex align-items-center">
                <span class="me-3 d-none d-lg-inline text-gray-600 small fw-bold"><?php echo htmlspecialchars($userId); ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </nav>

        <div class="container-fluid px-4">
            <div class="card log-card mb-4">
                <div class="card-header bg-dark py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-terminal me-2"></i>Fail2Ban 차단 내역 (실시간 5초 갱신)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Banned IP</th>
                                    <th>Ban Time</th>
                                    <th>Unban Time</th>
                                    <th>Jail Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="log-table-body">
                                <tr><td colspan='5' class='text-center py-4'>데이터를 불러오는 중입니다...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function refreshLogs() {
        fetch('fetch_logs.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('log-table-body').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    }

    setInterval(refreshLogs, 5000); 

    refreshLogs();
    </script>
</body>
</html>