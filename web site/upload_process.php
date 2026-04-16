<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$host = '100.64.27.39';
$user = 'jaewon'; 
$pass = 'jaewon'; 
$dbname = 'eoulrim_db'; 
$conn = mysqli_connect($host, $user, $pass, $dbname);

$title = "자료실 업로드";
$post_content = "첨부된 파일을 확인해주세요.";
$author = $_SESSION['userid'];

$file_msg = ""; 

// 1. 파일이 선택되었는지(에러 코드 0) 확인
if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = basename($_FILES["upload_file"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
        $file_msg = "\\n(첨부파일: $file_name)";

    } else {
        $file_msg = "\\n(파일 첨부 실패 - 권한 확인)";
    }
} else {
    $file_msg = "\\n(첨부된 파일 없음)";
    $title = "내용 없는 게시글";
}

$sql = "INSERT INTO team_board (title, post, author, reg_date) VALUES ('$title', '$post_content', '$author', NOW())";

if (mysqli_query($conn, $sql)) {
    echo "<script>
        alert('글이 성공적으로 등록되었습니다.' + '$file_msg');
        location.href = 'board.php';
    </script>";
} else {
    echo "데이터 저장 에러: " . mysqli_error($conn);
}

mysqli_close($conn);
?>