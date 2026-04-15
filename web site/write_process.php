<?php
session_start();
include 'db.php'; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userid'])) {
    exit('접근 권한이 없습니다.');
}

$post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
$author = mysqli_real_escape_string($conn, $_POST['author']);

$sql = "INSERT INTO team_board (post, author, reg_date) VALUES ('$post_content', '$author', NOW())";

if (mysqli_query($conn, $sql)) {
    echo "<script>
        alert('글이 성공적으로 등록되었습니다.');
        location.href = 'board.php';
    </script>";
} else {
    echo "Query Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>