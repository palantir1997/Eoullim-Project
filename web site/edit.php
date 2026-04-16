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
if ($row['author'] !== $_SESSION['userid']) {
    echo "<script>alert('본인이 작성한 글만 수정할 수 있습니다.'); history.back();</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow-sm w-100" style="max-width: 500px;">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 fw-bold text-primary">게시글 수정</h5>
        </div>
        <div class="card-body p-4">
            <form action="edit_process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idx" value="<?php echo $idx; ?>">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Title (제목)</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Post Content (내용)</label>
                    <textarea name="post_content" class="form-control" rows="3" required><?php echo htmlspecialchars($row['post']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Author (작성자)</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['author']); ?>" readonly>
                </div>

                <div class="mb-4 border-top pt-3 mt-3">
                    <label class="form-label fw-bold text-primary">첨부 파일 수정</label>
                    
                    <?php if (!empty($row['file_name'])) { ?>
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-secondary me-3">
                                <i class="fas fa-save me-1"></i> 현재 파일: <strong><?php echo htmlspecialchars($row['file_name']); ?></strong>
                            </span>
                            
                            <div class="form-check text-danger mb-0">
                                <input class="form-check-input" type="checkbox" name="delete_file" value="Y" id="deleteFileCheck">
                                <label class="form-check-label" for="deleteFileCheck">
                                    <i class="fas fa-trash-alt"></i> 이 파일 삭제하기
                                </label>
                            </div>
                        </div>
                        <div class="form-text mb-2 text-muted" style="font-size: 0.8em;">※ 새로운 파일을 선택하거나 '삭제하기'를 체크하면 기존 파일이 지워집니다.</div>
                    <?php } else { ?>
                        <div class="form-text mb-2" style="font-size: 0.8em;">현재 등록된 파일이 없습니다. 새로 추가할 수 있습니다.</div>
                    <?php } ?>
                    
                    <input type="file" name="upload_file" class="form-control">
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