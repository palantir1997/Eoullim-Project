<?php
$host = '100.64.27.39';
$user = 'jaewon';
$pass = 'jaewon';
$dbname = 'eoulrim_db';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) { exit("DB 연결 실패"); }

$sql = "SELECT banned_ip, ban_time, unban_time, jail_name, status FROM fail2ban_logs ORDER BY ban_time DESC LIMIT 15";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $statusClass = ($row['status'] == 'banned') ? 'bg-danger' : 'bg-success';
        $statusText = strtoupper($row['status']);
        
        echo "<div class='log-entry d-flex align-items-center py-1'>";
        echo "<span class='text-secondary me-2'>[" . $row['ban_time'] . "]</span>";
        echo "<span class='fw-bold me-2' style='color: #00ff00;'>" . $row['banned_ip'] . "</span>";
        echo "<span class='text-secondary me-2'> — </span>";
        echo "<span class='text-info me-auto'>[" . $row['jail_name'] . "]</span>";
        echo "<span class='badge {$statusClass} ms-2' style='font-size: 0.75rem; min-width: 80px;'>" . $statusText . "</span>";
        echo "</div>";
    }
} else {
    echo "<div class='py-2 text-secondary'>차단 내역이 없습니다.</div>";
}
mysqli_close($conn);
?>