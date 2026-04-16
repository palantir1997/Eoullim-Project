<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$target_dir = "uploads/"; 

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (isset($_FILES["upload_file"])) {
    $file_name = basename($_FILES["upload_file"]["name"]); 
    $target_file = $target_dir . $file_name; 

    if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
        echo "<script>
            alert('파일이 성공적으로 업로드되었습니다: $file_name');
            location.href='board.php';
        </script>";
    } else {
        echo "<script>
            alert('파일 업로드 중 서버 오류가 발생했습니다.');
            location.href='board.php';
        </script>";
    }
} else {
    echo "<script>alert('업로드할 파일이 없습니다.'); location.href='board.php';</script>";
}
?>