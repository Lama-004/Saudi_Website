<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAdminSession();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);
$errors = [];

$stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$place = $result->fetch_assoc();

if (!$place) {
    header("Location: dashboard.php");
    exit();
}

function uploadImage($file, $uploadDir) {
    if (empty($file['name'])) return '';
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return '';
    if ($file['size'] > 5 * 1024 * 1024) return '';
    $newName = uniqid('img_', true) . '.' . $ext;
    $dest = $uploadDir . $newName;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return 'uploads/images/' . $newName;
    }
    return '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name           = trim($_POST['name'] ?? '');
    $region         = trim($_POST['region'] ?? '');
    $classification = trim($_POST['classification'] ?? '');
    $description    = trim($_POST['description'] ?? '');
    $location       = trim($_POST['location'] ?? '');
    $features       = trim($_POST['features'] ?? '');
    $activities     = trim($_POST['activities'] ?? '');
    $landmarks      = trim($_POST['landmarks'] ?? '');

    if (empty($name))           $errors[] = 'اسم المكان مطلوب.';
    if (empty($region))         $errors[] = 'اسم المنطقة مطلوب.';
    if (empty($classification)) $errors[] = 'التصنيف مطلوب.';
    if (empty($description))    $errors[] = 'الوصف مطلوب.';

    if (empty($errors)) {
        $uploadDir = '../uploads/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $mainImage   = uploadImage($_FILES['main_image']    ?? [], $uploadDir) ?: $place['main_image'];
        $galleryImg1 = uploadImage($_FILES['gallery_image1'] ?? [], $uploadDir) ?: $place['gallery_image1'];
        $galleryImg2 = uploadImage($_FILES['gallery_image2'] ?? [], $uploadDir) ?: $place['gallery_image2'];
        $galleryImg3 = uploadImage($_FILES['gallery_image3'] ?? [], $uploadDir) ?: $place['gallery_image3'];

        $stmt = $conn->prepare("
            UPDATE places SET
                name=?, region=?, classification=?, description=?,
                location=?, features=?, activities=?, landmarks=?,
                main_image=?, gallery_image1=?, gallery_image2=?, gallery_image3=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "ssssssssssssi",
            $name, $region, $classification, $description,
            $location, $features, $activities, $landmarks,
            $mainImage, $galleryImg1, $galleryImg2, $galleryImg3,
            $id
        );

        if ($stmt->execute()) {
            header("Location: dashboard.php?msg=updated");
            exit();
        } else {
            $errors[] = 'حدث خطأ أثناء التحديث. حاول مجدداً.';
        }
    }

    $place['name']           = $_POST['name']           ?? $place['name'];
    $place['region']         = $_POST['region']         ?? $place['region'];
    $place['classification'] = $_POST['classification'] ?? $place['classification'];
    $place['description']    = $_POST['description']    ?? $place['description'];
    $place['location']       = $_POST['location']       ?? $place['location'];
    $place['features']       = $_POST['features']       ?? $place['features'];
    $place['activities']     = $_POST['activities']     ?? $place['activities'];
    $place['landmarks']      = $_POST['landmarks']      ?? $place['landmarks'];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المشرف - تحديث المحتوى</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="admin-navbar">
    <div class="admin-navbar-inner">
        <a href="dashboard.php" class="admin-brand">لوحة تحكم المشرف</a>
        <ul class="admin-nav-links">
            <li><a href="dashboard.php">لوحة التحكم</a></li>
            <li><a href="add.php">إضافة جديد</a></li>
            <li><a href="../index.php">زيارة الموقع</a></li>
            <li><a href="logout.php" class="logout-link">تسجيل الخروج</a></li>
        </ul>
    </div>
</nav>

<main class="admin-main">
    <h1 class="admin-page-title">تحديث مكان</h1>
    <p class="admin-page-subtitle">
        المكان المحدد للتحديث: <strong><?= htmlspecialchars($place['name']) ?></strong>
    </p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
             <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
        </div>
    <?php endif; ?>

    <div class="update-layout">

        <div class="admin-form-wrapper">
            <form method="POST" enctype="multipart/form-data">

                <p class="form-section-title">تعديل البيانات</p>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><span class="required">*</span> اسم المكان</label>
                        <input type="text" name="name" class="form-control"
                            value="<?= htmlspecialchars($place['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><span class="required">*</span> اسم المنطقة</label>
                        <input type="text" name="region" class="form-control"
                            value="<?= htmlspecialchars($place['region']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">تحديث الصورة الرئيسية (اختياري)</label>
                    <input type="file" name="main_image" class="form-control" accept="image/*">
                </div>

                <div class="form-group">
                    <label class="form-label"><span class="required">*</span> الوصف</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($place['description']) ?></textarea>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><span class="required">*</span> النوع</label>
                        <select name="classification" class="form-control" required>
                            <?php foreach (['وسطى', 'غربية', 'شرقية', 'جنوبية', 'شمالية'] as $c): ?>
                                <option value="<?= $c ?>" <?= $place['classification'] === $c ? 'selected' : '' ?>>
                                    <?= $c ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">الموقع</label>
                        <input type="text" name="location" class="form-control"
                            value="<?= htmlspecialchars($place['location'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">المميزات</label>
                    <input type="text" name="features" class="form-control"
                        value="<?= htmlspecialchars($place['features'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">الأنشطة</label>
                    <input type="text" name="activities" class="form-control"
                        value="<?= htmlspecialchars($place['activities'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">المعالم الأبرز</label>
                    <input type="text" name="landmarks" class="form-control"
                        value="<?= htmlspecialchars($place['landmarks'] ?? '') ?>">
                </div>

                <p class="form-section-title">تحديث صور المعرض (اختياري)</p>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">صورة المعرض الأولى</label>
                        <input type="file" name="gallery_image1" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label class="form-label">صورة المعرض الثانية</label>
                        <input type="file" name="gallery_image2" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label class="form-label">صورة المعرض الثالثة</label>
                        <input type="file" name="gallery_image3" class="form-control" accept="image/*">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-large">حفظ التحديثات</button>
            </form>
        </div>

        <div class="current-images-panel">
            <h4>الصورة الرئيسية الحالية</h4>
            <?php if (!empty($place['main_image']) && file_exists('../' . $place['main_image'])): ?>
                <img src="../<?= htmlspecialchars($place['main_image']) ?>"
                     alt="الصورة الرئيسية" class="current-img-thumb">
            <?php else: ?>
                <div style="width:100%;height:80px;background:linear-gradient(135deg,#1a6b3c,#145530);border-radius:8px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.4);font-size:1.5rem;margin-bottom:8px;">🏙️</div>
            <?php endif; ?>
            <span class="current-img-label">الصورة الرئيسية</span>

            <h4>صور المعرض الحالية</h4>
            <?php
            $galleryImages = [
                ['img' => $place['gallery_image1'], 'label' => 'صورة 1'],
                ['img' => $place['gallery_image2'], 'label' => 'صورة 2'],
                ['img' => $place['gallery_image3'], 'label' => 'صورة 3'],
            ];
            foreach ($galleryImages as $g):
                if (!empty($g['img'])):
            ?>
                <?php if (file_exists('../' . $g['img'])): ?>
                    <img src="../<?= htmlspecialchars($g['img']) ?>"
                         alt="<?= $g['label'] ?>" class="current-img-thumb">
                <?php else: ?>
                    <div style="width:100%;height:70px;background:#eee;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#aaa;margin-bottom:8px;">🖼️</div>
                <?php endif; ?>
                <span class="current-img-label"><?= $g['label'] ?></span>
            <?php
                endif;
            endforeach;
            ?>
        </div>

    </div>
</main>

</body>
</html>
