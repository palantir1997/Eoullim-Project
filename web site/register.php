<?php
$host = '100.64.27.39';
$user = 'jaewon';
$pass = 'jaewon';
$dbname = 'eoulrim_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

$uid = $_POST['userid'];
$upw = $_POST['password'];

$check_stmt = $conn->prepare("SELECT userid FROM users WHERE userid = ?");
$check_stmt->bind_param("s", $uid);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
    exit;
}

$hashed_pw = password_hash($upw, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (userid, password) VALUES (?, ?)");
$stmt->bind_param("ss", $uid, $hashed_pw);

if ($stmt->execute()) {
    echo "<script>
        alert('회원가입이 완료되었습니다! 로그인해 주세요.');
        location.href = 'login.php';
    </script>";
} else {
    echo "<script>alert('가입 중 오류가 발생했습니다.'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>