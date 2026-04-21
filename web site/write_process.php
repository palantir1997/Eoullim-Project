<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$conn = mysqli_connect('100.64.27.39', 'jaewon', 'jaewon', 'eoulrim_db');

mysqli_set_charset($conn, "utf8mb4");

if (isset($_POST['title'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    
    $file_name_db = ""; 

    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
        
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf', 'zip', 'hwp', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'); 
        
        $filename = $_FILES['upload_file']['name']; 
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 

        if (!in_array($ext, $allowed_ext)) {
            echo "<script>alert('보안상 위험한 파일 확장자(.php, .exe 등)는 업로드할 수 없습니다. 이미지나 문서 파일만 올려주세요.'); history.back();</script>";
            exit;
        }

        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . "_" . basename($_FILES["upload_file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
            $file_name_db = mysqli_real_escape_string($conn, $file_name);
        } else {
            echo "<script>alert('파일 이동 실패! 서버 권한을 확인하세요.');</script>";
        }
    }

    $sql = "INSERT INTO team_board (title, post, author, file_name, reg_date) 
            VALUES ('$title', '$post_content', '$author', '$file_name_db', NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('글이 등록되었습니다.'); location.href='board.php';</script>";
    }
}
?>