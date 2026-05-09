<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAdminSession();

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM places WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $successMsg = 'تم حذف السجل بنجاح.';
    } else {
        $errorMsg = 'حدث خطأ أثناء الحذف.';
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added')   $successMsg = 'تمت إضافة السجل بنجاح.';
    if ($_GET['msg'] === 'updated') $successMsg = 'تم تحديث السجل بنجاح.';
}

$result = $conn->query("SELECT * FROM places ORDER BY id ASC");
$places = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المشرف - لوحة التحكم</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="admin-navbar">
    <div class="admin-navbar-inner">
        <a href="dashboard.php" class="admin-brand">لوحة تحكم المشرف</a>
        <ul class="admin-nav-links">
            <li><a href="../index.php">زيارة الموقع</a></li>
            <li>
               <button class="night-toggle" id="night-toggle-btn">
                 الوضع الليلي
              </button>
           </li>
           <li><a href="logout.php" class="logout-link">تسجيل الخروج</a></li>

        </ul>
    </div>
</nav>

<main class="admin-main">
    <h1 class="admin-page-title">إدارة المحتوى</h1>
    <p class="admin-page-subtitle">استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافة أو تعديل أو حذف المحتوى.</p>

    <?php if ($successMsg): ?>
        <div class="alert alert-success"> <?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
        <div class="alert alert-error"> <?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <a href="add.php" class="btn-add">إضافة سجل جديد</a>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>المنطقة</th>
                    <th>التصنيف</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($places)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:var(--text-light);">
                            لا توجد سجلات حتى الآن.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($places as $place): ?>
                        <tr>
                            <td><?= $place['id'] ?></td>
                            <td><strong><?= htmlspecialchars($place['name']) ?></strong></td>
                            <td>
                                <?php
                                $badgeClass = [
                                    'وسطى'   => 'badge-center',
                                    'غربية'  => 'badge-west',
                                    'شرقية'  => 'badge-east',
                                    'جنوبية' => 'badge-south',
                                    'شمالية' => 'badge-north',
                                ][$place['classification']] ?? 'badge-center';
                                ?>
                                <span class="badge <?= $badgeClass ?>">
                                    <?= htmlspecialchars($place['classification']) ?>
                                </span>
                            </td>
                            <td class="desc-cell"><?= htmlspecialchars($place['description']) ?></td>
                            <td>
                                <a href="update.php?id=<?= $place['id'] ?>" class="btn btn-warning">تعديل</a>
                                <!-- زر الحذف يفتح نافذة تأكيد -->
                                <button
                                    class="btn btn-danger"
                                    onclick="confirmDelete(<?= $place['id'] ?>, '<?= htmlspecialchars($place['name'], ENT_QUOTES) ?>')"
                                >حذف</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<form id="delete-form" method="POST" style="display:none;">
    <input type="hidden" name="delete_id" id="delete-id-input">
</form>

<script>
function confirmDelete(id, name) {
    const confirmed = confirm(`هل تريد حذف هذا السجل؟\n"${name}"`);
    if (confirmed) {
        document.getElementById('delete-id-input').value = id;
        document.getElementById('delete-form').submit();
    }
}
</script>
<script src="../js/main.js"></script>

</body>
</html>
