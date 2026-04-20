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

    $check_sql = "SELECT author, image_file FROM board_comments WHERE idx = $comment_idx";
    $result = mysqli_query($conn, $check_sql);
    $row = mysqli_fetch_assoc($result);

    if ($row['author'] === $_SESSION['userid']) {
        if (!empty($row['image_file']) && file_exists("uploads/" . $row['image_file'])) {
            unlink("uploads/" . $row['image_file']);
        }
        mysqli_query($conn, "DELETE FROM board_comments WHERE idx = $comment_idx");
        echo "<script>alert('댓글이 삭제되었습니다.'); location.href='view.php?idx=$board_idx';</script>";
    } else {
        echo "<script>alert('본인이 작성한 댓글만 삭제할 수 있습니다.'); history.back();</script>";
    }
}
?>