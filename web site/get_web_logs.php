<?php
$logPath = '/var/log/httpd/access_log';

if (file_exists($logPath)) {
    $output = shell_exec("tail -n 15 " . escapeshellarg($logPath));
    if (!$output) {
        echo "로그 파일이 비어있거나 읽을 수 없습니다.";
        exit;
    }

    $lines = explode("\n", trim($output));
    
    foreach ($lines as $line) {
        if (empty(trim($line))) continue;
        $parts = explode(' ', $line);
        
        $ip = $parts[0]; 
        $time = str_replace('[', '', $parts[3]); 
        
        $method = isset($parts[5]) ? str_replace('"', '', $parts[5]) : '-';
        $path = isset($parts[6]) ? $parts[6] : '-';
        $status = isset($parts[8]) ? $parts[8] : '-';

        echo "<div class='log-entry'>";
        echo "<span style='color: #888;'>[$time]</span> ";
        echo "<span style='color: #00ff00; font-weight: bold;'>$ip</span> ";
        echo "── $method $path ";
        echo "<span class='badge " . ($status == '200' ? 'bg-success' : 'bg-danger') . "'>$status</span>";
        echo "</div>";
    }
} else {
    echo "로그 파일을 찾을 수 없습니다: $logPath";
}
?>