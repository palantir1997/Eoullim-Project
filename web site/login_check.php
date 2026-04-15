<?php
session_start();

$host = '100.64.27.39';
$user = 'jaewon';
$pass = 'jaewon';
$dbname = 'eoulrim_db';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("DB 연결 실패: " . mysqli_connect_error());
}

$uid = $_POST['userid'];
$upw = $_POST['password'];

$stmt = $conn->prepare("SELECT userid, password FROM users WHERE userid = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
     if (password_verify($upw, $row['password'])) {
        $_SESSION['userid'] = $row['userid'];

        echo "<script>
            alert('로그인에 성공했습니다! 메인 페이지로 이동합니다.');
            location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('아이디 또는 비밀번호가 틀렸습니다.');
            history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('아이디 또는 비밀번호가 틀렸습니다.');
        history.back();
    </script>";
}

$stmt->close();
mysqli_close($conn);
?>