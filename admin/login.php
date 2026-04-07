<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';

if (is_admin_authenticated()) {
    redirect('admin/dashboard.php');
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $username = input('username');
    $password = input('password');

    if ($username === config('admin.username') && $password === config('admin.password')) {
        $_SESSION['is_admin'] = true;
        flash('success', '登录成功。');
        redirect('admin/dashboard.php');
    }

    $error = '账号或密码错误。';
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="<?= h(asset('public/admin.css')) ?>">
</head>
<body class="admin-login-page">
    <main class="login-card">
        <h1>管理后台登录</h1>
        <p>默认账号信息在 <code>includes/config.php</code> 中，请上线前修改。</p>

        <?php if ($error): ?>
            <div class="flash error"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="post" class="stack">
            <input type="hidden" name="_token" value="<?= h(csrf_token()) ?>">
            <label>
                <span>用户名</span>
                <input type="text" name="username" required>
            </label>
            <label>
                <span>密码</span>
                <input type="password" name="password" required>
            </label>
            <button type="submit">登录</button>
        </form>

        <a class="back-link" href="<?= h(url('index.php')) ?>">返回前台</a>
    </main>
</body>
</html>
