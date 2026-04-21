<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$conn = mysqli_connect('100.64.27.39', 'jaewon', 'jaewon', 'eoulrim_db');

mysqli_set_charset($conn, "utf8mb4");

if (isset($_POST['board_idx']) && isset($_POST['content'])) {
    $board_idx = (int)$_POST['board_idx'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author = $_SESSION['userid'];
    $parent_idx = !empty($_POST['parent_idx']) ? (int)$_POST['parent_idx'] : "NULL";
    
    $image_file_db = "NULL"; 

    if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] == 0) {
        
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf', 'zip'); 
        $filename = $_FILES['comment_image']['name']; 
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 

        if (!in_array($ext, $allowed_ext)) {
            echo "<script>alert('보안상 위험한 파일 확장자(.php, .exe 등)는 업로드할 수 없습니다.'); history.back();</script>";
            exit;
        }

        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = time() . "_" . basename($_FILES["comment_image"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["comment_image"]["tmp_name"], $target_file)) {
            $image_file_db = "'" . mysqli_real_escape_string($conn, $file_name) . "'";
        }
    }

    $sql = "INSERT INTO board_comments (board_idx, parent_idx, author, content, image_file, reg_date) 
            VALUES ($board_idx, $parent_idx, '$author', '$content', $image_file_db, NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>location.href='view.php?idx=$board_idx';</script>";
    }
}
mysqli_close($conn);
?>