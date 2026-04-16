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
        $unbanTime = $row['unban_time'] ? $row['unban_time'] : 'Active';
        $statusText = strtoupper($row['status']);
        
        echo "[" . $row['ban_time'] . "] ";
        echo "IP: " . str_pad($row['banned_ip'], 15) . " | ";
        echo "Jail: [" . $row['jail_name'] . "] | ";
        echo "Status: " . $statusText . " (" . $unbanTime . ")\n";
    }
} else {
    echo "<tr><td colspan='5' class='text-center py-4'>차단 데이터가 없습니다.</td></tr>";
}

mysqli_close($conn);
?>