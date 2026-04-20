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
    
    $current_user = trim(strtolower($_SESSION['userid']));

    $check_sql = "
        SELECT c.author AS comment_author, c.image_file, b.author AS post_author 
        FROM board_comments c 
        JOIN team_board b ON c.board_idx = b.idx 
        WHERE c.idx = $comment_idx
    ";
    
    $result = mysqli_query($conn, $check_sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $comment_author = trim(strtolower($row['comment_author']));
        $post_author = trim(strtolower($row['post_author']));

        if ($current_user === $comment_author || $current_user === $post_author) {
            
            if (!empty($row['image_file']) && file_exists("uploads/" . $row['image_file'])) {
                unlink("uploads/" . $row['image_file']);
            }
            
            mysqli_query($conn, "DELETE FROM board_comments WHERE idx = $comment_idx");
            echo "<script>alert('댓글이 삭제되었습니다.'); location.href='view.php?idx=$board_idx';</script>";
            
        } else {
            echo "<script>alert('삭제 권한이 없습니다. (본인 댓글 또는 본인의 게시글에서만 삭제 가능)'); history.back();</script>";
        }
    } else {
        echo "<script>alert('이미 삭제되었거나 존재하지 않는 댓글입니다.'); history.back();</script>";
    }
}
?>