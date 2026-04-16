<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$conn = mysqli_connect('100.64.27.39', 'jaewon', 'jaewon', 'eoulrim_db');

if (isset($_POST['idx']) && isset($_POST['title']) && isset($_POST['post_content'])) {
    $idx = (int)$_POST['idx'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);

    $check_sql = "SELECT author, file_name FROM team_board WHERE idx = $idx";
    $check_result = mysqli_query($conn, $check_sql);
    $row = mysqli_fetch_assoc($check_result);

    if ($row['author'] !== $_SESSION['userid']) {
        echo "<script>alert('본인이 작성한 글만 수정할 수 있습니다.'); history.back();</script>";
        exit;
    }

    $old_file = $row['file_name'];
    $update_file_sql = ""; 

    if (isset($_FILES['upload_file']) && $_FILES['upload_file']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = basename($_FILES["upload_file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
            if (!empty($old_file) && file_exists($target_dir . $old_file)) {
                unlink($target_dir . $old_file); 
            }
            $file_name_db = mysqli_real_escape_string($conn, $file_name);
            $update_file_sql = ", file_name = '$file_name_db'"; 
        }
    } 
    else if (isset($_POST['delete_file']) && $_POST['delete_file'] === 'Y') {
        if (!empty($old_file) && file_exists("uploads/" . $old_file)) {
            unlink("uploads/" . $old_file); 
        }
        $update_file_sql = ", file_name = ''"; 
    }

    $sql = "UPDATE team_board SET title = '$title', post = '$post_content' $update_file_sql WHERE idx = $idx";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('게시글이 성공적으로 수정되었습니다.'); location.href='board.php';</script>";
    } else {
        echo "수정 에러: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('잘못된 접근입니다.'); location.href='board.php';</script>";
}

mysqli_close($conn);
?>