<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$conn = mysqli_connect('100.64.27.39', 'jaewon', 'jaewon', 'eoulrim_db');

if (isset($_POST['board_idx']) && isset($_POST['content'])) {
    
    $board_idx = (int)$_POST['board_idx'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author = $_SESSION['userid'];
    
    $parent_idx = !empty($_POST['parent_idx']) ? (int)$_POST['parent_idx'] : "NULL";

    $sql = "INSERT INTO board_comments (board_idx, parent_idx, author, content, reg_date) 
            VALUES ($board_idx, $parent_idx, '$author', '$content', NOW())";

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