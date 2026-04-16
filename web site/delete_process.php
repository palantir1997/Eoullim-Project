<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('회원만 이용할 수 있습니다. 로그인해주세요!'); location.href='login.php';</script>";
    exit;
}

$host = '100.64.27.39';
$user = 'jaewon'; 
$pass = 'jaewon'; 
$dbname = 'eoulrim_db'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

if ($idx > 0) {
    $sql = "DELETE FROM team_board WHERE idx = $idx";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('게시글이 성공적으로 삭제되었습니다.'); location.href='board.php';</script>";
    } else {
        echo "삭제 에러: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('잘못된 접근입니다.'); location.href='board.php';</script>";
}

mysqli_close($conn);
?>