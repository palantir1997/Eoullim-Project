<?php
session_start();

include 'db.php'; 

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('접근 권한이 없습니다.'); location.href='login.php';</script>";
    exit;
}

if (isset($_POST['post_content']) && isset($_POST['author'])) {
    
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);

    $sql = "INSERT INTO team_board (post, author, reg_date) VALUES ('$post_content', '$author', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('글이 성공적으로 등록되었습니다.');
            location.href = 'board.php';
        </script>";
    } else {
        echo "DB 에러: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('잘못된 접근입니다.'); location.href='board.php';</script>";
}

mysqli_close($conn);
?>