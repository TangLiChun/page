<?php

declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';

session_unset();
session_destroy();

session_start();
flash('success', '已退出登录。');

redirect('admin/login.php');
