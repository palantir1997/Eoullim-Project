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

mysqli_query($conn, "UPDATE team_board SET views = views + 1 WHERE idx = $idx");

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                
                <?php 
                if ($_SESSION['userid'] === $row['author']) { 
                ?>
                    <a href="edit.php?idx=<?php echo $row['idx']; ?>" class="btn btn-primary">수정하기</a>
                    <a href="delete_process.php?idx=<?php echo $row['idx']; ?>" class="btn btn-danger" onclick="return confirm('정말 삭제하시겠습니까?');">삭제하기</a>
                <?php 
                } 
                ?>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light py-2">
                <h6 class="m-0 fw-bold"><i class="fas fa-comments text-secondary me-1"></i> 댓글</h6>
            </div>
            <div class="card-body p-4">
                
                <?php
                $comment_sql = "SELECT * FROM board_comments WHERE board_idx = $idx ORDER BY IFNULL(parent_idx, idx) ASC, idx ASC";
                $comment_result = mysqli_query($conn, $comment_sql);
                
                if ($comment_result && mysqli_num_rows($comment_result) > 0) {
                    while ($c_row = mysqli_fetch_assoc($comment_result)) {
                        $is_reply = !empty($c_row['parent_idx']);
                ?>
                        <div class="mb-3 border-bottom pb-3 <?php echo $is_reply ? 'ms-4 ps-3 border-start border-3 border-light' : ''; ?>">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <?php if($is_reply) { ?><i class="fas fa-reply fa-rotate-180 text-muted me-1"></i><?php } ?>
                                    <strong><?php echo htmlspecialchars($c_row['author']); ?></strong>
                                </div>
                                <span class="text-muted" style="font-size: 0.8em;"><?php echo $c_row['reg_date']; ?></span>
                            </div>
                            <div style="font-size: 0.95em;" class="mb-2">
                                <?php echo nl2br(htmlspecialchars($c_row['content'])); ?>
                            </div>
                            
                            <?php if (!$is_reply) { ?>
                                <button class="btn btn-sm btn-light text-secondary p-1" style="font-size: 0.8em;" 
                                        onclick="document.getElementById('reply_<?php echo $c_row['idx']; ?>').classList.toggle('d-none')">
                                    <i class="fas fa-comment-dots"></i> 
                                </button>
                                
                                <form action="comment_process.php" method="POST" id="reply_<?php echo $c_row['idx']; ?>" class="mt-2 d-none d-flex gap-2">
                                    <input type="hidden" name="board_idx" value="<?php echo $idx; ?>">
                                    <input type="hidden" name="parent_idx" value="<?php echo $c_row['idx']; ?>">
                                    <input type="text" name="content" class="form-control form-control-sm" placeholder="답글을 입력하세요..." required>
                                    <button type="submit" class="btn btn-sm btn-secondary">등록</button>
                                </form>
                            <?php } ?>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-muted small mb-4'>등록된 댓글이 없습니다. 첫 번째 댓글을 남겨보세요!</p>";
                }
                ?>
                
                <form action="comment_process.php" method="POST" class="mt-2">
                    <input type="hidden" name="board_idx" value="<?php echo $idx; ?>">
                    <div class="input-group">
                        <textarea name="content" class="form-control" rows="2" placeholder="댓글을 남겨보세요..." required></textarea>
                        <button type="submit" class="btn btn-primary px-4">등록</button>
                    </div>
                </form>
                
            </div>
        </div>
        </div>
</body>
</html>