<?php
session_start();

include 'db.php'; 

if (!isset($_SESSION['userid'])) {
    exit('접근 권한이 없습니다.');
}


$post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
$author = mysqli_real_escape_string($conn, $_POST['author']);

$sql = "INSERT INTO team_board (post, author) VALUES ('$post_content', '$author')";

if (mysqli_query($conn, $sql)) {
    echo "<script>
        alert('글이 성공적으로 등록되었습니다.');
        location.href = 'board.php';
    </script>";
} else {
    echo "에러: " . mysqli_error($conn);
}

mysqli_close($conn);
?>