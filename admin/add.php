<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/db.php';
checkAdminSession();

$errors = [];

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

    $uploadDir = '../uploads/images/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    function uploadImage($file, $uploadDir) {
        if (empty($file['name'])) return '';
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) return '';
        if ($file['size'] > 5 * 1024 * 1024) return ''; // 5MB max
        $newName = uniqid('img_', true) . '.' . $ext;
        $dest = $uploadDir . $newName;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return 'uploads/images/' . $newName;
        }
        return '';
    }

    $mainImage    = uploadImage($_FILES['main_image'] ?? [], $uploadDir);
    $galleryImg1  = uploadImage($_FILES['gallery_image1'] ?? [], $uploadDir);
    $galleryImg2  = uploadImage($_FILES['gallery_image2'] ?? [], $uploadDir);
    $galleryImg3  = uploadImage($_FILES['gallery_image3'] ?? [], $uploadDir);

    if (empty($errors)) {
        $stmt = $conn->prepare("
            INSERT INTO places
                (name, region, classification, description, location, features, activities, landmarks,
                 main_image, gallery_image1, gallery_image2, gallery_image3)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssssssssss",
            $name, $region, $classification, $description,
            $location, $features, $activities, $landmarks,
            $mainImage, $galleryImg1, $galleryImg2, $galleryImg3
        );

        if ($stmt->execute()) {
            header("Location: dashboard.php?msg=added");
            exit();
        } else {
            $errors[] = 'حدث خطأ أثناء حفظ البيانات. حاول مجدداً.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المشرف - إضافة محتوى</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="admin-navbar">
    <div class="admin-navbar-inner">
        <a href="dashboard.php" class="admin-brand">لوحة تحكم المشرف</a>
        <ul class="admin-nav-links">
            <li><a href="dashboard.php">لوحة التحكم</a></li>
            <li><a href="../index.php">زيارة الموقع</a></li>
            <li><a href="logout.php" class="logout-link">تسجيل الخروج</a></li>
        </ul>
        
    </div>
</nav>

<main class="admin-main">
    <h1 class="admin-page-title">إضافة مكان جديد</h1>
    <p class="admin-page-subtitle">أدخل معلومات المنطقة أو المكان الجديد.</p>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
             <?= implode('<br> ', array_map('htmlspecialchars', $errors)) ?>
        </div>
    <?php endif; ?>

    <div class="admin-form-wrapper">
        <form method="POST" enctype="multipart/form-data">

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label"><span class="required">*</span> اسم المكان</label>
                    <input type="text" name="name" class="form-control"
                        placeholder="مثال: الرياض"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label"><span class="required">*</span> اسم المنطقة</label>
                    <input type="text" name="region" class="form-control"
                        placeholder="مثال: منطقة الرياض"
                        value="<?= htmlspecialchars($_POST['region'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label"><span class="required">*</span> الصورة الرئيسية للمكان</label>
                <input type="file" name="main_image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-label"><span class="required">*</span> الوصف</label>
                <textarea name="description" class="form-control" rows="4"
                    placeholder="اكتب وصفاً تفصيلياً للمكان..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label"><span class="required">*</span> النوع</label>
                    <select name="classification" class="form-control" required>
                        <option value="">-- اختر النوع --</option>
                        <?php foreach (['وسطى', 'غربية', 'شرقية', 'جنوبية', 'شمالية'] as $c): ?>
                            <option value="<?= $c ?>" <?= (($_POST['classification'] ?? '') === $c) ? 'selected' : '' ?>>
                                <?= $c ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">الموقع</label>
                    <input type="text" name="location" class="form-control"
                        placeholder="مثال: شمال غرب المملكة"
                        value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">المميزات</label>
                <input type="text" name="features" class="form-control"
                    placeholder="مثال: موقع أثري - طبيعة خلابة (افصل بشرطة)"
                    value="<?= htmlspecialchars($_POST['features'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">الأنشطة</label>
                <input type="text" name="activities" class="form-control"
                    placeholder="مثال: تسلق الجبال - زيارة أثرية (افصل بشرطة)"
                    value="<?= htmlspecialchars($_POST['activities'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label class="form-label">المعالم الأبرز (افصل بينها بشرطة)</label>
                <input type="text" name="landmarks" class="form-control"
                    placeholder="مثال: برج المملكة - قصر المصمك - حديقة الحيوانات"
                    value="<?= htmlspecialchars($_POST['landmarks'] ?? '') ?>">
            </div>

            <p class="form-section-title">صور المعرض</p>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label"><span class="required">*</span> صورة المعرض الأولى</label>
                    <input type="file" name="gallery_image1" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">صورة المعرض الثانية (اختياري)</label>
                    <input type="file" name="gallery_image2" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">صورة المعرض الثالثة (اختياري)</label>
                    <input type="file" name="gallery_image3" class="form-control" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-large">إضافة المكان</button>
        </form>
    </div>
</main>

</body>
</html>
