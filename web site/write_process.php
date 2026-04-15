<?php
session_start();

if (!isset($_SESSION['userid'])) {
    exit('접근 권한이 없습니다.');
}

$host = "localhost";
$user = "root";      
$pass = "";          
$dbname = "aulim";   

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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
    echo "에러: " . mysqli_error($conn);
}

mysqli_close($conn);
?>