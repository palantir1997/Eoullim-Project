<?php
session_start();

include 'db.php'; 

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('접근 권한이 없습니다.'); location.href='login.php';</script>";
    exit;
}

if (!isset($conn)) {
    if (isset($db)) {
        $conn = $db;
    } elseif (isset($mysqli)) {
        $conn = $mysqli;
    } else {
        die("에러: db.php에서 DB 연결 변수를 찾을 수 없습니다.");
    }
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
        echo "Query Error: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('데이터가 누락되었습니다.'); history.back();</script>";
}

mysqli_close($conn);
?>