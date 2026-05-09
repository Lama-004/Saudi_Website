<?php
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'يرجى إدخال اسم المستخدم وكلمة المرور.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المشرف - تسجيل الدخول</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>


<nav class="admin-navbar">
    <div class="admin-navbar-inner">
        <a href="#" class="admin-brand">لوحة المشرف</a>
        <ul class="admin-nav-links">
            <li><a href="../index.php">زيارة الموقع</a></li>
            <li>
               <button class="night-toggle" id="night-toggle-btn">
                 الوضع الليلي
              </button>
           </li>
        </ul>
    </div>
</nav>

<div class="login-page">
    <div class="login-card">
        <h2 class="login-title">تسجيل دخول المشرف</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <div class="form-group">
                <label class="form-label">اسم المستخدم</label>
                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="مثال: admin"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    required
                >
            </div>
            <div class="form-group">
                <label class="form-label">كلمة المرور</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>
            <button type="submit" class="btn btn-primary btn-large" style="margin-top:10px;">
                دخول
            </button>
        </form>

        <p style="text-align:center; margin-top:20px; font-size:0.8rem; color:#999;">
            بيانات الدخول الافتراضية: admin / @admin1234
        </p>
    </div>
</div>
<script src="../js/main.js"></script>

</body>
</html>
