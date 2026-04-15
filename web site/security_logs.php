<?php
session_start();

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('권한이 없습니다. 로그인 후 이용해주세요.'); location.href='login.php';</script>";
    exit;
}

$host = '100.64.27.39';
$user = 'jaewon';
$pass = 'jaewon';
$dbname = 'eoulrim_db';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("보안 로그 DB 연결 실패: " . mysqli_connect_error());
}

$sql = "SELECT banned_ip, ban_time, unban_time, jail_name, status FROM fail2ban_logs ORDER BY ban_time DESC LIMIT 20";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Aulim Security - IDS Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fc; padding: 2rem; }
        .log-card { border-radius: 1rem; overflow: hidden; border: none; box-shadow: 0 0.5rem 2rem rgba(0,0,0,0.1); }
        .status-banned { color: #e74a3b; fw-bold; }
        .status-unbanned { color: #1cc88a; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold"><i class="fas fa-user-shield me-2"></i>Fail2Ban 차단 내역 (IDS)</h1>
        <a href="index.php" class="btn btn-sm btn-secondary shadow-sm"><i class="fas fa-arrow-left me-1"></i> 대시보드로 돌아가기</a>
    </div>

    <div class="card log-card">
        <div class="card-header bg-dark py-3">
            <h6 class="m-0 font-weight-bold text-white">실시간 침입 탐지 로그 (최근 20건)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Banned IP</th>
                            <th>Ban Time</th>
                            <th>Unban Time</th>
                            <th>Jail Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $statusClass = ($row['status'] == 'banned') ? 'bg-danger' : 'bg-success';
                                $unbanTime = $row['unban_time'] ? $row['unban_time'] : '<span class="text-muted">-</span>';
                                
                                echo "<tr class='text-center'>";
                                echo "<td class='fw-bold text-primary'>{$row['banned_ip']}</td>";
                                echo "<td>{$row['ban_time']}</td>";
                                echo "<td>{$unbanTime}</td>";
                                echo "<td><span class='badge bg-secondary'>{$row['jail_name']}</span></td>";
                                echo "<td><span class='badge {$statusClass}'>" . strtoupper($row['status']) . "</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-4'>데이터가 없습니다.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
</body>
</html>