<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('회원만 이용할 수 있습니다. 로그인해주세요!'); location.href='login.php';</script>";
    exit;
}

$host = '100.64.27.39';
$user = 'jaewon'; 
$pass = 'jaewon'; 
$dbname = 'eoulrim_db'; 
$conn = mysqli_connect($host, $user, $pass, $dbname);

$idx = isset($_GET['idx']) ? (int)$_GET['idx'] : 0;

$sql = "SELECT * FROM team_board WHERE idx = $idx";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(!$row) {
    echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='board.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 읽기</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="m-0 fw-bold text-primary"><?php echo htmlspecialchars($row['title']); ?></h4>
                <div class="text-muted small mt-2">
                    <span>작성자: <?php echo htmlspecialchars($row['author']); ?></span> | 
                    <span>등록일: <?php echo $row['reg_date']; ?></span>
                </div>
            </div>
            <div class="card-body p-4" style="min-height: 300px;">
                <p class="fs-5"><?php echo nl2br(htmlspecialchars($row['post'])); ?></p>
            </div>
            <div class="card-footer bg-white py-3 text-end">
                <a href="board.php" class="btn btn-secondary">목록으로</a>
                <a href="edit.php?idx=<?php echo $row['idx']; ?>" class="btn btn-primary">수정하기</a>
            </div>
        </div>
    </div>
</body>
</html>