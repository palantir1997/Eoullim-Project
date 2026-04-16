<?php
session_start();
if (!isset($_SESSION['userid'])) {
    echo "<script>
        alert('회원만 이용할 수 있습니다. 로그인해주세요!');
        location.href = 'login.php';
    </script>";
    exit;
} 

$host = '100.64.27.39';
$user = 'jaewon'; 
$pass = 'jaewon'; 
$dbname = 'eoulrim_db'; 

$conn = mysqli_connect($host, $user, $pass, $dbname); 
$sql = "SELECT * FROM team_board ORDER BY idx DESC"; 
$result = mysqli_query($conn, $sql);

$userId = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulim Security Portal - Team Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fc; }
        .sidebar { min-height: 100vh; background-color: #1c2331; }
        .sidebar .nav-link { color: rgba(255,255,255,.7); padding: 1rem 1.5rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: rgba(255,255,255,.1); }
        .sidebar .navbar-brand { color: #fff; font-weight: bold; padding: 1.5rem 1rem; text-align: center; display: block; text-decoration: none; }
        .content-wrapper { flex: 1; display: flex; flex-direction: column; }
        .topbar { background-color: #fff; box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15); }
    </style>
</head>
<body class="d-flex">

    <div class="sidebar d-flex flex-column flex-shrink-0" style="width: 250px;">
        <a href="index.php" class="navbar-brand border-bottom border-secondary mb-3">
            <i class="fas fa-shield-alt me-2"></i>Aulim Security
        </a>
       <ul class="nav flex-column mb-auto">
    <li>
        <a href="board.php" class="nav-link active">
            <i class="fas fa-fw fa-table me-2"></i> Team Board
        </a>
    </li>
    <li>
        <a href="index.php" class="nav-link">
            <i class="fas fa-fw fa-tachometer-alt me-2"></i> Admin Dashboard
        </a>
    </li>
    <li>
        <a href="web_access_monitor.php" class="nav-link">
            <i class="fas fa-fw fa-terminal me-2"></i> Web Monitor
        </a>
    </li>
    <li>
        <a href="security_logs.php" class="nav-link">
            <i class="fas fa-fw fa-user-shield me-2"></i> Security Logs
        </a>
    </li>
    <li>
        <a href="communication.php" class="nav-link">
            <i class="fas fa-fw fa-comments me-2"></i> Team Communication
        </a>
    </li>
</ul>
    </div>

    <div class="content-wrapper">
        <nav class="navbar topbar mb-4 px-4 py-3 d-flex justify-content-between align-items-center">
            <form class="d-none d-sm-inline-block form-inline mr-auto" style="width: 350px;">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for...">
                    <button class="btn btn-primary" type="button"><i class="fas fa-search fa-sm"></i></button>
                </div>
            </form>
            <ul class="navbar-nav align-items-center flex-row mb-0">
                <li class="nav-item d-flex align-items-center">
                    <span class="me-2 d-none d-lg-inline text-gray-600 small fw-bold">
                        <?php echo htmlspecialchars($userId); ?> (Online)
                    </span>
                    <i class="fas fa-user-circle fa-2x text-gray-400 me-3"></i>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger fw-bold">Logout</a>
                </li>
            </ul>
        </nav>

        <div class="container-fluid px-4">
            <h3 class="h4 mb-4 text-gray-800 fw-bold">Team Board</h3>

            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
                    <h6 class="m-0 fw-bold text-primary">Team Board List</h6>
                    <button class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i> New Post</button>
                </div>
                <div class="card-body">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 8%;">번호</th>
                            <th style="width: 37%;">제목</th>
                            <th style="width: 10%;">파일</th> <th style="width: 15%;">작성자</th>
                            <th style="width: 15%;">등록일시</th>
                            <th style="width: 15%;">관리</th> </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            $num = mysqli_num_rows($result);
                            while($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <tr>
                                    <td><?php echo $num--; ?></td>
                                    <td class="text-start ps-3">
                                        <a href="view.php?idx=<?php echo $row['idx']; ?>" class="text-decoration-none text-dark fw-bold">
                                            <?php echo htmlspecialchars($row['title']); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['file_name'])) { ?>
                                            <i class="fas fa-paperclip text-secondary" title="<?php echo htmlspecialchars($row['file_name']); ?>"></i>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                                    <td><?php echo $row['reg_date']; ?></td>
                                    <td>
                                        <?php if ($_SESSION['userid'] === $row['author']) { ?>
                                            <a href="edit.php?idx=<?php echo $row['idx']; ?>" class="btn btn-sm btn-outline-secondary">수정</a>
                                            <a href="delete_process.php?idx=<?php echo $row['idx']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('삭제하시겠습니까?');">삭제</a>
                                        <?php } else { echo "<span class='text-muted' style='font-size:0.8em;'>권한없음</span>"; } ?>
                                    </td>
                                </tr>
                        <?php } } else { echo "<tr><td colspan='6' class='text-center py-4'>등록된 게시글이 없습니다.</td></tr>"; } ?>
                    </tbody>
                </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="newPostModal" tabindex="-1" aria-labelledby="newPostModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newPostModalLabel">새 게시글 작성</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="write_process.php" method="POST">
                        <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Title (제목)</label>
                            <input type="text" name="title" class="form-control" placeholder="게시글 제목을 입력하세요" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Post Content (내용)</label>
                            <textarea name="post_content" class="form-control" rows="3" placeholder="게시글 내용을 입력하세요" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Author (작성자)</label>
                            <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($userId); ?>" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                        <button type="submit" class="btn btn-primary">등록하기</button>
                    </div>
                    <div class="card shadow-sm border-left-primary">
            <div class="card-body">
                <h6 class="fw-bold text-primary mb-3">File Upload (자료실)</h6>
                <form action="upload_process.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <input type="file" name="upload_file" class="form-control" id="inputGroupFile04">
                        <button class="btn btn-primary" type="submit" id="inputGroupFileAddon04">
                            <i class="fas fa-upload me-1"></i> Upload
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        window.onload = function() {
                    const btn = document.querySelector('.btn-primary.btn-sm');
                    if(btn) {
                        btn.setAttribute('data-bs-toggle', 'modal');
                        btn.setAttribute('data-bs-target', '#newPostModal');
                    }
                };
        </script>

            </body>
        </html>