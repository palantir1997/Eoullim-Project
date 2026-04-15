<?php
$host = '100.64.27.39';
$user = 'jaewon';
$pass = 'jaewon';
$dbname = 'eoulrim_db';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    exit("DB 연결 실패");
}

$sql = "SELECT banned_ip, ban_time, unban_time, jail_name, status FROM fail2ban_logs ORDER BY ban_time DESC LIMIT 20";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $statusClass = ($row['status'] == 'banned') ? 'bg-danger' : 'bg-success';
        $unbanTime = $row['unban_time'] ? $row['unban_time'] : '-';
        
        echo "<tr class='text-center'>";
        echo "<td class='fw-bold text-primary'>{$row['banned_ip']}</td>";
        echo "<td>{$row['ban_time']}</td>";
        echo "<td>{$unbanTime}</td>";
        echo "<td><span class='badge bg-secondary'>{$row['jail_name']}</span></td>";
        echo "<td><span class='badge {$statusClass}'>" . strtoupper($row['status']) . "</span></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center py-4'>차단 데이터가 없습니다.</td></tr>";
}

mysqli_close($conn);
?>