<?php
$logPath = '/var/log/httpd/access_log';

if (file_exists($logPath)) {
    $lines = explode("\n", shell_exec("tail -n 15 " . escapeshellarg($logPath)));
    
    foreach ($lines as $line) {
        if (empty(trim($line))) continue;

        $parts = explode(' ', $line);
        
        $ip = $parts[0]; // IP
        $time = str_replace(['[', ']'], '', $parts[3]); // 시간 부분
        $method = str_replace('"', '', $parts[5]); // GET/POST
        $path = $parts[6]; // 경로
        $status = $parts[8]; // 응답 코드

        echo "<div class='log-entry'>";
        echo "<span style='color: #888;'>[$time]</span> ";
        echo "<span style='color: #00ff00; font-weight: bold;'>$ip</span> ";
        echo "── $method $path ";
        echo "<span class='badge " . ($status == '200' ? 'bg-success' : 'bg-danger') . "'>$status</span>";
        echo "</div>";
    }
} else {
    echo "로그 없음";
}
?>