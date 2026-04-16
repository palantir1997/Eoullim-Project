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
$userId = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 수정</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow-sm w-100" style="max-width: 500px;">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 fw-bold text-primary">게시글 수정</h5>
        </div>
        <div class="card-body p-4">
            <form action="edit_process.php" method="POST">
                <input type="hidden" name="idx" value="<?php echo $idx; ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Title (제목)</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Post Content (내용)</label>
                    <textarea name="post_content" class="form-control" rows="3" required><?php echo htmlspecialchars($row['post']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Author (작성자)</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['author']); ?>" readonly>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="history.back();">취소</button>
                    <button type="submit" class="btn btn-primary">수정완료</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>