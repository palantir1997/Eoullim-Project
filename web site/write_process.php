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

if (isset($_POST['title']) && isset($_POST['post_content']) && isset($_POST['author'])) {
    
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);

    $file_name_to_db = ""; 
    $file_msg = ""; 

    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = basename($_FILES["upload_file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
            $file_msg = "\\n(첨부파일 업로드 완료)";
            $file_name_to_db = mysqli_real_escape_string($conn, $file_name);
        } else {
            $file_msg = "\\n(파일 첨부 실패 - 서버 권한 확인 필요)";
        }
    }

    $sql = "INSERT INTO team_board (title, post, author, file_name, reg_date) 
            VALUES ('$title', '$post_content', '$author', '$file_name_to_db', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('글이 성공적으로 등록되었습니다.' + '$file_msg');
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