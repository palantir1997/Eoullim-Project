<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aulim Security - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #1c2331; /* 사이드바와 동일한 진한 남색 계열 */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 1rem;
            background: #ffffff;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.3);
        }
        .brand-logo {
            font-size: 2rem;
            font-weight: bold;
            color: #1c2331;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .btn-login {
            background-color: #1c2331;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: bold;
            width: 100%;
            transition: background 0.3s;
        }
        .btn-login:hover {
            background-color: #2c3e50;
            color: #fff;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 1.5rem 0;
        }
        .register-link {
            color: #1c2331;
            text-decoration: none;
            font-weight: bold;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

   <div class="login-card">
    <div class="brand-logo">
        <i class="fas fa-shield-alt me-2"></i>Aulim Security
    </div>

    <form action="login_check.php" method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">User ID</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                <input type="text" name="userid" class="form-control bg-light border-start-0" placeholder="아이디를 입력하세요" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-secondary">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="비밀번호를 입력하세요" required>
            </div>
        </div>

        <button type="submit" class="btn btn-login mb-3">Login</button>

        <div class="divider"></div>

        <div class="text-center">
            <p class="small text-muted mb-0">계정이 없으신가요?</p>
            <a href="register_form.php" class="register-link">새로운 계정 만들기 (회원가입)</a>
        </div>
    </form>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
