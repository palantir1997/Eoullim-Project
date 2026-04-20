<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$conn = mysqli_connect('100.64.27.39', 'jaewon', 'jaewon', 'eoulrim_db');

if (isset($_POST['board_idx']) && isset($_POST['content'])) {
    $board_idx = (int)$_POST['board_idx'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author = $_SESSION['userid'];
    $parent_idx = !empty($_POST['parent_idx']) ? (int)$_POST['parent_idx'] : "NULL";
    
    $image_file_db = "NULL"; 

    if (isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        // 중복 방지를 위해 파일명 앞에 현재 시간을 붙여줍니다 (예: 1690000000_photo.jpg)
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