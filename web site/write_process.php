<?php
session_start();

$host = '100.64.27.39';
$user = 'jaewon'; 
$pass = 'jaewon'; 
$dbname = 'eoulrim_db'; 

$conn = mysqli_connect($host, $user, $pass, $dbname); 

if (!$conn) {
    die("DB 서버 연결 실패: " . mysqli_connect_error());
}

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
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
        echo "데이터 저장 에러: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('정상적인 접근이 아닙니다.'); location.href='board.php';</script>";
}

mysqli_close($conn);
?>