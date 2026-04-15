<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulim Security Portal - Admin Dashboard</title>
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
        <a href="board.php" class="navbar-brand border-bottom border-secondary mb-3">
            <i class="fas fa-shield-alt me-2"></i>Aulim Security
        </a>
        <ul class="nav flex-column mb-auto">
            <li><a href="board.php" class="nav-link"><i class="fas fa-fw fa-table me-2"></i> Team Board</a></li>
            <li><a href="index.php" class="nav-link active"><i class="fas fa-fw fa-tachometer-alt me-2"></i> Admin Dashboard</a></li>
            <li><a href="communication.php" class="nav-link"><i class="fas fa-fw fa-comments me-2"></i> Team Communication</a></li>
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
                    <span class="me-2 d-none d-lg-inline text-gray-600 small fw-bold" id="userLabel">Guest</span>
                    <i class="fas fa-user-circle fa-2x text-gray-400 me-3"></i>
                    <button id="authBtn" class="btn btn-sm btn-outline-primary fw-bold" onclick="handleAuth()">Login</button>
                </li>
            </ul>
        </nav>

        <div class="container-fluid px-4 text-center">
            <h3 class="h4 mb-4 text-start text-gray-800 fw-bold">Admin Dashboard - Network Status</h3>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header py-3 bg-dark text-start">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-server me-2"></i>Server Status Monitor (Host-name based)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped border-0 mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Host Name</th>
                                <th>Service / Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">WEB-SVR-01</td>
                                <td>Apache</td>
                                <td><span class="badge bg-success">OK</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">DB-SVR-01</td>
                                <td>MariaDB/MySQL</td>
                                <td><span class="badge bg-success">OK</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">GW-ROUTER</td>
                                <td>VPN / EIGRP</td>
                                <td><span class="badge bg-success">OK</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">GRE Tunnel</td>
                                <td>Status</td>
                                <td><span class="badge bg-success">OK</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function checkLoginStatus() {
            const authBtn = document.getElementById('authBtn');
            const userLabel = document.getElementById('userLabel');
            const token = localStorage.getItem('token'); 
            const userId = localStorage.getItem('user_id');

            if (token && userId) {
                userLabel.textContent = userId + " (Online)";
                authBtn.textContent = 'Logout';
                authBtn.className = 'btn btn-sm btn-outline-danger fw-bold';
            } else {
                userLabel.textContent = "Guest";
                authBtn.textContent = 'Login';
                authBtn.className = 'btn btn-sm btn-outline-primary fw-bold';
            }
        }

        function handleAuth() {
            if (localStorage.getItem('token')) {
                localStorage.removeItem('token');
                localStorage.removeItem('user_id');
                alert('로그아웃 되었습니다.');
                window.location.href = 'login.php';
            } else {
                window.location.href = 'login.php';
            }
        }

        checkLoginStatus();
    </script>
</body>
</html>
</body>
</html>