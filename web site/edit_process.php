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


if (isset($_POST['idx']) && isset($_POST['title']) && isset($_POST['post_content'])) {
    $idx = (int)$_POST['idx'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);

    $sql = "UPDATE team_board SET title = '$title', post = '$post_content' WHERE idx = $idx";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('게시글이 성공적으로 수정되었습니다.'); location.href='board.php';</script>";
    } else {
        echo "수정 에러: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('정상적인 접근이 아닙니다.'); location.href='board.php';</script>";
}

mysqli_close($conn);
?>