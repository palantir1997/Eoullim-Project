<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); history.back();</script>";
    exit;
}

$conn = mysqli_connect('100.64.27.39', 'jaewon', 'jaewon', 'eoulrim_db');

if (isset($_GET['comment_idx']) && isset($_GET['board_idx'])) {
    $comment_idx = (int)$_GET['comment_idx'];
    $board_idx = (int)$_GET['board_idx'];

    mysqli_query($conn, "UPDATE board_comments SET likes = likes + 1 WHERE idx = $comment_idx");
    
    echo "<script>location.href='view.php?idx=$board_idx';</script>";
}
?>