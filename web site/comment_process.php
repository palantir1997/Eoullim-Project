<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$host = '100.64.27.39';
$user = 'jaewon'; 
$pass = 'jaewon'; 
$dbname = 'eoulrim_db'; 
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (isset($_POST['board_idx']) && isset($_POST['content'])) {
    
    $board_idx = (int)$_POST['board_idx'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author = $_SESSION['userid']; 

    $sql = "INSERT INTO board_comments (board_idx, author, content, reg_date) 
            VALUES ($board_idx, '$author', '$content', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>location.href='view.php?idx=$board_idx';</script>";
    } else {
        echo "데이터 저장 에러: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('정상적인 접근이 아닙니다.'); history.back();</script>";
}

mysqli_close($conn);
?>